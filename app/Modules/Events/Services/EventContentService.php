<?php

namespace App\Modules\Events\Services;

use App\Models\Events\Event;
use App\Modules\Events\Actions\UpdateEventContentAction;
use App\Modules\Events\DTOs\EventContentData;

class EventContentService
{
    public function __construct(
        private UpdateEventContentAction $updateContentAction,
    ) {}

    public function update(Event $event, EventContentData $data): Event
    {
        return $this->updateContentAction->execute($event, $data);
    }
}
