<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use Illuminate\Support\Facades\DB;

class DeleteEventAction
{
    /**
     * Delete the model in a transaction.
     */
    public function execute(Event $event): void
    {
        DB::transaction(function () use ($event) {
            // Example: $event->clearMediaCollection();
            $event->deleteOrFail();
        });
    }
}
