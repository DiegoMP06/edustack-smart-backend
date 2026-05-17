<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;

class ToggleEventStatusAction
{
    public function execute(Event $event): Event
    {
        $event->is_published = ! $event->is_published;
        $event->published_at = $event->is_published ? now() : null;
        $event->save();

        return $event;
    }
}
