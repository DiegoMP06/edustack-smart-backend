<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventContentData;

class UpdateEventContentAction
{
    public function execute(Event $event, EventContentData $data): Event
    {
        $event->content = $data->content;
        $event->save();

        return $event;
    }
}
