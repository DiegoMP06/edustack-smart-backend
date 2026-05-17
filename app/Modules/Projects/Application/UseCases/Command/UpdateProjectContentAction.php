<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class UpdateProjectContentAction
{
    public function execute(Project $project, ModelContentFormData $data): Project
    {
        $project->content = $data->content;
        $project->save();

        return $project;
    }
}
