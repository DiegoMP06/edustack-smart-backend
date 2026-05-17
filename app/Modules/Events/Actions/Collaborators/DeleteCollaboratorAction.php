<?php

namespace App\Modules\Events\Actions\Collaborators;

use App\Models\Events\Event;
use App\Models\Events\EventCollaborator;

class DeleteCollaboratorAction
{
    /**
     * Execute the action.
     */
    public function execute(Event $event, EventCollaborator $collaborator): Event
    {
        abort_if($collaborator->event_id !== $event->id, 404);

        $collaborator->delete();

        return $event;
    }
}
