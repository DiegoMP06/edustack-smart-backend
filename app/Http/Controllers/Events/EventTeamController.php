<?php

namespace App\Http\Controllers\Events;

use App\Enums\Events\TeamMemberRole;
use App\Enums\Events\TeamStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventTeamRequest;
use App\Http\Requests\Events\UpdateEventTeamRequest;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityTeam;
use App\Models\Events\EventActivityTeamMember;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventTeamController extends Controller
{
    private function ensureActivityBelongsToEvent(Event $event, EventActivity $activity): void
    {
        abort_if($activity->event_id !== $event->id, 404);
    }

    private function ensureTeamBelongsToActivity(EventActivity $activity, EventActivityTeam $team): void
    {
        abort_if($team->event_activity_id !== $activity->id, 404);
    }

    public function store(StoreEventTeamRequest $request, Event $event, EventActivity $activity)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);
        $user = $request->user();
        $data = $request->validated();

        if (! $activity->has_teams) {
            throw ValidationException::withMessages([
                'activity' => 'Esta actividad no permite equipos.',
            ]);
        }

        $alreadyInTeam = EventActivityTeamMember::query()
            ->where('user_id', $user->id)
            ->whereHas('team', fn ($query) => $query->where('event_activity_id', $activity->id))
            ->exists();

        if ($alreadyInTeam) {
            throw ValidationException::withMessages([
                'team' => 'Ya perteneces a un equipo en esta actividad.',
            ]);
        }

        $team = $activity->teams()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'captain_user_id' => $user->id,
            'status' => TeamStatus::FORMING,
        ]);

        $team->members()->create([
            'user_id' => $user->id,
            'role' => TeamMemberRole::CAPTAIN,
        ]);

        return back()->with('message', 'Equipo creado correctamente.');
    }

    public function update(UpdateEventTeamRequest $request, Event $event, EventActivity $activity, EventActivityTeam $team)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);
        $this->ensureTeamBelongsToActivity($activity, $team);
        $data = $request->validated();

        if ($team->captain_user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $team->update($data);

        return back()->with('message', 'Equipo actualizado correctamente.');
    }

    public function join(Request $request, Event $event, EventActivity $activity, EventActivityTeam $team)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);
        $this->ensureTeamBelongsToActivity($activity, $team);
        $user = $request->user();

        if ($team->status !== TeamStatus::FORMING) {
            throw ValidationException::withMessages([
                'team' => 'Este equipo ya no acepta miembros.',
            ]);
        }

        if ($activity->max_team_size !== null && $team->members()->count() >= $activity->max_team_size) {
            throw ValidationException::withMessages([
                'team' => 'El equipo ya alcanzó su tamaño máximo.',
            ]);
        }

        $alreadyInTeam = EventActivityTeamMember::query()
            ->where('user_id', $user->id)
            ->whereHas('team', fn ($query) => $query->where('event_activity_id', $activity->id))
            ->exists();

        if ($alreadyInTeam) {
            throw ValidationException::withMessages([
                'team' => 'Ya perteneces a un equipo en esta actividad.',
            ]);
        }

        $team->members()->create([
            'user_id' => $user->id,
            'role' => TeamMemberRole::MEMBER,
        ]);

        return back()->with('message', 'Te uniste al equipo correctamente.');
    }

    public function leave(Request $request, Event $event, EventActivity $activity, EventActivityTeam $team)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);
        $this->ensureTeamBelongsToActivity($activity, $team);

        $member = $team->members()->where('user_id', $request->user()->id)->firstOrFail();
        $isCaptain = $team->captain_user_id === $request->user()->id;

        if ($isCaptain && $team->members()->count() > 1) {
            throw ValidationException::withMessages([
                'team' => 'Debes transferir la capitanía antes de salir.',
            ]);
        }

        if ($team->members()->count() === 1) {
            $team->delete();

            return back()->with('message', 'Equipo eliminado.');
        }

        $member->delete();

        return back()->with('message', 'Saliste del equipo.');
    }
}
