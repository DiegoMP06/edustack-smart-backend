<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventRequest;
use App\Http\Requests\Events\UpdateEventRequest;
use App\Http\Resources\Events\EventCollection;
use App\Models\Events\Event;
use App\Concerns\ApiQueryable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EventController extends Controller
{
    use ApiQueryable;

    public function index(Request $request)
    {
        $events = $this->buildQuery(
            $request->user()->events(),
            defaultIncludes: ['status', 'media']
        )
            ->paginate(20)->withQueryString();

        return Inertia::render('events/events', [
            'events' => new EventCollection($events),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create()
    {
        return Inertia::render('events/create-event');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        $event = $request->user()->events()->create([
            ...$data,
            'content' => [],
            'event_status_id' => 1,
        ]);

        $event->addMediaFromDisk($data['logo'], 's3')
            ->toMediaCollection('logo');

        return redirect()->intended(
            route('events.content.edit', ['event' => $event, 'edit' => false], false)
        )->with('message', 'Evento creado correctamente.');
    }

    public function show(Event $event, Request $request)
    {
        return Inertia::render('events/show-event', [
            'event' => (new EventCollection([$event->load(['activities', 'author', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function edit(Request $request, Event $event)
    {
        return Inertia::render('events/edit-event', [
            'event' => (new EventCollection([$event->load(['activities', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if (
            $event->activities()->where(
                fn($q) => $q->where('started_at', '<', $data['start_date'])
                    ->orWhere('ended_at', '>', $data['end_date'])
            )->exists()
        ) {
            throw ValidationException::withMessages([
                'start_date' => 'Las fechas del evento no pueden estar fuera del rango de fechas de las actividades.',
            ]);
        }

        $event->update($data);

        if ($request->has('logo')) {
            $event->clearMediaCollection('logo');
            $event->addMediaFromDisk($data['logo'], 's3')
                ->toMediaCollection('logo');
        }

        return back()->with('message', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return back()->with('message', 'Evento eliminado correctamente.');
    }
}
