<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventContentController extends Controller
{
    public function edit(Event $event, Request $request)
    {
        $edit = $request->boolean('edit', false);

        return Inertia::render('events/event-content', [
            'event' => $event,
            'edit' => $edit,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $edit = $request->boolean('edit', false);
        $data = $request->validate([
            'content' => ['required', 'array'],
            'content.*.props' => ['required', 'array'],
            'content.*.type' => ['required', 'string'],
        ]);

        $event->content = $data['content'];
        $event->save();

        $route = $edit ?
            back() :
            redirect()->intended(route(
                'event-collaborators.index',
                ['event' => $event, 'edit' => false],
                false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
