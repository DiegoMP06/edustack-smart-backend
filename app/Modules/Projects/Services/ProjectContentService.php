<?php

namespace App\Modules\Projects\Services;

use App\Models\Projects\Project;
use App\Modules\Projects\Actions\UpdateProjectContentAction;
use App\Modules\Projects\DTOs\ProjectContentData;

class ProjectContentService
{
    public function __construct(
        private UpdateProjectContentAction $updateContentAction,
    ) {}

    public function update(Project $project, ProjectContentData $data): Project
    {
        return $this->updateContentAction->execute($project, $data);
    }
}
