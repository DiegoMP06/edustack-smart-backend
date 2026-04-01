<?php

namespace App\Http\Controllers\Events\Activity;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;

class EventActivityStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Event $event, EventActivity $activity)
    {
        $activity->is_published = !$activity->is_published;
        $activity->save();

        return back()->with('message', 'Estado de la actividad actualizado.');
    }
}
