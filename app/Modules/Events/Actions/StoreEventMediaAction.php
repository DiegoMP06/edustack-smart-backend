<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventMediaData;

class StoreEventMediaAction
{
    public function execute(Event $event, EventMediaData $data): void
    {
        foreach ($data->images as $key) {
            $event->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }
    }
}
