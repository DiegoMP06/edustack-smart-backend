<?php

namespace App\Modules\Events\Services;

use App\Models\Events\Event;
use App\Modules\Events\Actions\ToggleEventStatusAction;
use App\Modules\Events\DTOs\EventStatusData;

class EventStatusService
{
    public function __construct(
        private ToggleEventStatusAction $toggleStatusAction,
    ) {}

    public function toggle(Event $event): Event
    {
        $data = EventStatusData::fromModel($event);

        return $this->toggleStatusAction->execute($event, $data);
    }
}
