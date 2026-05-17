<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectCollaboratorFormData;

class StoreCollaboratorAction
{
    /**
     * Execute the action.
     */
    public function execute(ProjectCollaboratorFormData $data, Project $project): Project
    {
        abort_if(
            $project->collaborators()->wherePivot('user_id', $data->user_id)->exists(),
            422,
            'El colaborador ya pertenece al proyecto.'
        );

        $project->collaborators()->attach($data->user_id, ['role' => $data->role]);

        return $project;
    }
}
