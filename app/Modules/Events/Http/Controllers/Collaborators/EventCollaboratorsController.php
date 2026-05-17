<?php

namespace App\Modules\Events\Http\Controllers\Collaborators;

use App\Http\Requests\Events\StoreEventCollaboratorRequest;
use App\Models\Events\Event;
use App\Models\Events\EventCollaborator;
use App\Modules\Admin\Http\Resources\UserCollection;
use App\Modules\Events\Actions\Collaborators\DeleteCollaboratorAction;
use App\Modules\Events\Actions\Collaborators\StoreCollaboratorAction;
use App\Modules\Events\DTOs\EventCollaboratorFormData;
use App\Modules\Events\DTOs\EventData;
use App\Modules\Events\Http\Controllers\Controller;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use App\Modules\Shared\Services\UsersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EventCollaboratorsController extends Controller
{
    public function __construct(
        protected UsersService $usersService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event): Response
    {
        $this->authorize('update', $event);

        $data = ListCollectionQueryParamsData::fromRequest($request);
        $users = $this->usersService->listActiveUsers($data, $request->user());

        return inertia('events/event-collaborators', [
            'event' => EventData::fromModel(
                $event->load('collaborators')
            )->include('collaborators'),
            'users' => new UserCollection($users),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
            'edit' => $request->boolean('edit', false),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Event $event,
        StoreEventCollaboratorRequest $request,
        StoreCollaboratorAction $action,
    ): RedirectResponse {
        $this->authorize('update', $event);

        $data = EventCollaboratorFormData::from($request->validated());
        $action->execute($data, $event);

        return back()->with('message', 'Colaborador agregado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Event $event,
        EventCollaborator $eventCollaborator,
        DeleteCollaboratorAction $action
    ): RedirectResponse {
        $this->authorize('update', $event);

        $action->execute($event, $eventCollaborator);

        return back()->with('message', 'Colaborador eliminado correctamente.');
    }
}
