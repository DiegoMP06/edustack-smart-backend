<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventData;
use Illuminate\Support\Facades\DB;

class UpdateEventAction
{
    /**
     * Update an existing model using DTO data.
     */
    public function execute(Event $event, EventData $data): Event
    {
        return DB::transaction(function () use ($event) {
            $event->update([
                // Map DTO properties to model attributes.
            ]);

            return $event->load([]);
        });
    }
}
