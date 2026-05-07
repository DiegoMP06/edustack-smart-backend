<?php

namespace App\Modules\Projects\Services;

use App\Models\Projects\Project;
use App\Modules\Projects\Actions\ToggleProjectStatusAction;
use App\Modules\Projects\DTOs\ProjectStatusData;

class ProjectStatusService
{
    public function __construct(
        private ToggleProjectStatusAction $toggleStatusAction,
    ) {}

    public function toggle(Project $project): Project
    {
        $data = ProjectStatusData::fromModel($project);

        return $this->toggleStatusAction->execute($project, $data);
    }
}
