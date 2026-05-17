<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class UpdateEventContentAction
{
    public function execute(Event $event, ModelContentFormData $data): Event
    {
        $event->content = $data->content;
        $event->save();

        return $event;
    }
}
