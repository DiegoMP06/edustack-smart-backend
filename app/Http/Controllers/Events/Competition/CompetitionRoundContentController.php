<?php

namespace App\Http\Controllers\Events\Competition;

use App\Http\Controllers\Controller;
use App\Models\Events\CompetitionRound;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompetitionRoundContentController extends Controller
{
    public function edit(Event $event, EventActivity $activity, CompetitionRound $round, Request $request)
    {
        $edit = $request->boolean('edit', false);

        return Inertia::render('events/rounds/round-content', [
            'event' => $event,
            'activity' => $activity,
            'round' => $round,
            'edit' => $edit,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(Request $request, Event $event, EventActivity $activity, CompetitionRound $round)
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
                'events.activities.rounds.edit',
                ['event' => $event, 'activity' => $activity, 'round' => $round],
                absolute: false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
