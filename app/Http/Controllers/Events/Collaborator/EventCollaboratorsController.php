<?php

namespace App\Http\Controllers\Events\Collaborator;

use App\Concerns\ApiQueryable;
use App\Enums\Events\EventCollaboratorRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventCollaboratorRequest;
use App\Http\Resources\UserCollection;
use App\Models\Events\Event;
use App\Models\Events\EventCollaborator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EventCollaboratorsController extends Controller
{
    use ApiQueryable;

    public function index(Event $event, Request $request)
    {
        $this->authorize('update', $event);

        $users = $users = $this->buildQuery(
            User::where(
                fn($query) =>
                $query->whereNot('id', $request->user()?->id)
                    ->where('is_active', true)
            ),
        )->paginate(20)->withQueryString();

        return Inertia::render('events/event-collaborators', [
            'users' => new UserCollection($users),
            'filter' => $request->query('filter'),
            'event' => $event->load('collaborators'),
            'roles' => EventCollaboratorRole::cases(),
            'message' => $request->session()->get('message'),
            'edit' => $request->boolean('edit', false),
        ]);
    }

    public function store(Event $event, StoreEventCollaboratorRequest $request)
    {
        $this->authorize('update', $event);

        $data = $request->validated();

        if ($event->collaborators()->wherePivot('user_id', $data['user_id'])->exists()) {
            throw ValidationException::withMessages(['user_id' => 'El colaborador ya pertenece al proyecto.']);
        }

        $event->collaborators()->attach($data['user_id'], ['role' => $data['role']]);

        return back()->with('message', 'Colaborador agregado correctamente.');
    }

    public function destroy(Event $event, EventCollaborator $eventCollaborator)
    {
        $this->authorize('update', $event);

        abort_if($eventCollaborator->event_id !== $event->id, 404);

        $eventCollaborator->delete();

        return back()->with('message', 'Colaborador eliminado correctamente.');
    }
}
