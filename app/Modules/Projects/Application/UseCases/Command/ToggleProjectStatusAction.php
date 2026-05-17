<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;

class ToggleProjectStatusAction
{
    public function execute(Project $project): Project
    {
        $project->is_published = ! $project->is_published;
        $project->published_at = $project->is_published ? now() : null;
        $project->save();

        return $project;
    }
}
