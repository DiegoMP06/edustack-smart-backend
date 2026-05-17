<?php

namespace App\Modules\Events\Http\Controllers;

use App\Models\Events\Event;
use App\Modules\Events\Actions\CreateEventAction;
use App\Modules\Events\Actions\DeleteEventAction;
use App\Modules\Events\Actions\UpdateEventAction;
use App\Modules\Events\DTOs\DraftEventFormData;
use App\Modules\Events\DTOs\EventData;
use App\Modules\Events\Http\Requests\StoreEventRequest;
use App\Modules\Events\Http\Requests\UpdateEventRequest;
use App\Modules\Events\Http\Resources\EventCollection;
use App\Modules\Events\Services\EventService;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $data = ListCollectionQueryParamsData::fromRequest($request);
        $events = $this->eventService->listUserEvents(
            $data,
            $request->user()
        );

        return inertia('events/events', [
            'events' => new EventCollection($events),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('events/create-event');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreEventRequest $request,
        CreateEventAction $action
    ): RedirectResponse {
        $data = DraftEventFormData::from($request->validated());
        $event = $action->execute($data, $request->user());

        return redirect()->intended(
            route('events.content.edit', ['event' => $event, 'edit' => false], false)
        )->with('message', 'Evento creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): Response
    {
        return inertia('events/show-event', [
            'event' => EventData::fromModel($event->load('activities', 'media', 'author', 'collaborators'))
                ->include('activities', 'media', 'author', 'collaborators'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): Response
    {
        return inertia('events/edit-event', [
            'event' => EventData::fromModel($event->load('media'))
                ->include('media'),
            'message' => request()->session()->get('message'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateEventRequest $request,
        Event $event,
        UpdateEventAction $action
    ): RedirectResponse {
        $data = DraftEventFormData::from($request->validated());
        $action->execute($event, $data);

        return back()->with('message', 'Evento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Event $event,
        DeleteEventAction $action
    ): RedirectResponse {
        $action->execute($event);

        return back()->with('message', 'Evento eliminado correctamente.');
    }
}
