<?php

namespace App\Modules\Events\DTOs;

use App\Models\Events\Event;

readonly class EventStatusData
{
    public function __construct(
        public bool $isActive,
    ) {}

    public static function fromModel(Event $event): self
    {
        return new self(
            isActive: ! $event->is_published,
        );
    }
}
