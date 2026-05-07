<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectContentData;

class UpdateProjectContentAction
{
    public function execute(Project $project, ProjectContentData $data): Project
    {
        $project->content = $data->content;
        $project->save();

        return $project;
    }
}
