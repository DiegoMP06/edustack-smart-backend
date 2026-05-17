<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use App\Models\Projects\ProjectCollaborator;

class DeleteCollaboratorAction
{
    /**
     * Execute the action.
     */
    public function execute(Project $project, ProjectCollaborator $collaborator): Project
    {
        abort_if($collaborator->project_id !== $project->id, 404);

        $collaborator->delete();

        return $project;
    }
}
