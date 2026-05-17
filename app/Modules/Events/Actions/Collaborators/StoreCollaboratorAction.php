<?php

namespace App\Modules\Events\Actions\Collaborators;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventCollaboratorFormData;

class StoreCollaboratorAction
{
    /**
     * Execute the action.
     */
    public function execute(EventCollaboratorFormData $data, Event $event): Event
    {
        abort_if(
            $event->collaborators()->wherePivot('user_id', $data->user_id)->exists(),
            422,
            'El colaborador ya pertenece al evento.'
        );

        $event->collaborators()->attach($data->user_id, ['role' => $data->role]);

        return $event;
    }
}
