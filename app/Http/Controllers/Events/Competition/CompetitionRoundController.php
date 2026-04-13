<?php

namespace App\Http\Controllers\Events\Competition;

use App\Concerns\ApiQueryable;
use App\Enums\Events\RoundStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreCompetitionRoundRequest;
use App\Http\Requests\Events\UpdateCompetitionRoundRequest;
use App\Http\Resources\Events\CompetitionRoundCollection;
use App\Http\Resources\Events\EventActivityCollection;
use App\Http\Resources\Events\EventCollection;
use App\Models\Events\CompetitionRound;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CompetitionRoundController extends Controller
{
    use ApiQueryable;

    private function ensureRoundBelongsToActivity(EventActivity $activity, CompetitionRound $round): void
    {
        abort_if($round->event_activity_id !== $activity->id, 404);
    }

    private function validateCompetition(Event $event, EventActivity $activity): void
    {
        abort_if($activity->event_id !== $event->id, 404);

        if (! $activity->is_competition) {
            throw ValidationException::withMessages([
                'activity' => 'Solo se pueden crear rondas en actividades de tipo competencia.',
            ]);
        }
    }

    public function index(Request $request, Event $event, EventActivity $activity)
    {
        $this->validateCompetition($event, $activity);

        $rounds = $this->buildQuery(
            $activity->rounds()
        )->paginate(20)->withQueryString();

        return Inertia::render('events/activities/rounds/index', [
            'event' => (new EventCollection([$event->load(['status', 'media', 'author'])]))->first(),
            'activity' => (new EventActivityCollection([$activity->load(['status', 'media'])]))->first(),
            'rounds' => new CompetitionRoundCollection($rounds),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create(Event $event, EventActivity $activity)
    {
        $this->validateCompetition($event, $activity);

        return Inertia::render('events/activities/rounds/create', [
            'event' => $event,
            'activity' => $activity,
        ]);
    }

    public function store(StoreCompetitionRoundRequest $request, Event $event, EventActivity $activity)
    {
        $this->validateCompetition($event, $activity);

        $data = $request->validated();
        $data['round_number'] = ((int) $activity->rounds()->max('round_number')) + 1;
        $data['status'] = RoundStatus::PENDING;

        $activity->rounds()->create($data);

        return back()->with('message', 'Ronda creada correctamente.');
    }

    public function show(Event $event, EventActivity $activity, CompetitionRound $round, Request $request)
    {
        $this->validateCompetition($event, $activity);
        $this->ensureRoundBelongsToActivity($activity, $round);

        return Inertia::render('events/activities/rounds/show', [
            'event' => $event,
            'activity' => $activity,
            'round' => $round->load('exercises'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function edit(Event $event, EventActivity $activity, CompetitionRound $round, Request $request)
    {
        $this->validateCompetition($event, $activity);
        $this->ensureRoundBelongsToActivity($activity, $round);

        return Inertia::render('events/activities/rounds/edit', [
            'event' => $event,
            'activity' => $activity,
            'round' => $round,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateCompetitionRoundRequest $request, Event $event, EventActivity $activity, CompetitionRound $round)
    {
        $this->validateCompetition($event, $activity);
        $this->ensureRoundBelongsToActivity($activity, $round);

        $round->update($request->validated());

        return back()->with('message', 'Ronda actualizada correctamente.');
    }

    public function destroy(Event $event, EventActivity $activity, CompetitionRound $round)
    {
        $this->validateCompetition($event, $activity);
        $this->ensureRoundBelongsToActivity($activity, $round);

        if ($round->status !== RoundStatus::PENDING) {
            throw ValidationException::withMessages([
                'round' => 'Solo se pueden eliminar rondas en estado pendiente.',
            ]);
        }

        $round->delete();

        return back()->with('message', 'Ronda eliminada correctamente.');
    }
}
