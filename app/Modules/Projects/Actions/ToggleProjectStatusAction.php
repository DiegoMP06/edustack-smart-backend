<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectStatusData;

class ToggleProjectStatusAction
{
    public function execute(Project $project, ProjectStatusData $data): Project
    {
        $project->is_published = $data->isActive;
        $project->published_at = $data->isActive ? now() : null;
        $project->save();

        return $project;
    }
}
