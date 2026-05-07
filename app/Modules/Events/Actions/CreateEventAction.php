<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventData;
use Illuminate\Support\Facades\DB;

class CreateEventAction
{
    /**
     * Persist a new model using DTO data.
     */
    public function execute(EventData $data, int $userId): Event
    {
        return DB::transaction(function () use ($userId) {
            $event = Event::create([
                // Map DTO properties to model attributes.
                'user_id' => $userId,
            ]);

            // Example: $event->addMedia($data->file)->toMediaCollection('default');

            return $event;
        });
    }
}
