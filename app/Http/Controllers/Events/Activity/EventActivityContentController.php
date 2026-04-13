<?php

namespace App\Http\Controllers\Events\Activity;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventActivityContentController extends Controller
{
    public function edit(Event $event, EventActivity $activity, Request $request)
    {
        $edit = $request->boolean('edit', false);

        return Inertia::render('events/activities/activity-content', [
            'event' => $event,
            'activity' => $activity,
            'edit' => $edit,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(Request $request, Event $event, EventActivity $activity)
    {
        $edit = $request->boolean('edit', false);
        $data = $request->validate([
            'content' => ['required', 'array'],
            'content.*.props' => ['required', 'array'],
            'content.*.type' => ['required', 'string'],
        ]);

        $activity->content = $data['content'];
        $activity->save();

        $route = $edit ?
            back() :
            redirect()->intended(route(
                'events.activities.edit',
                ['event' => $event, 'activity' => $activity],
                absolute: false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
