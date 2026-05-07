<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventStatusData;

class ToggleEventStatusAction
{
    public function execute(Event $event, EventStatusData $data): Event
    {
        $event->is_published = $data->isActive;
        $event->published_at = $data->isActive ? now() : null;
        $event->save();

        return $event;
    }
}
