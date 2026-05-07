<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetEventAction
{
    /**
     * Retrieve a model by its primary key.
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id): Event
    {
        $event = Event::find($id);

        if (! $event) {
            throw new ModelNotFoundException("The record with ID {$id} was not found.");
        }

        // If you prefer returning a DTO, map it here and adjust the return type.

        return $event;
    }
}
