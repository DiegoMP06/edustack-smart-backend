<?php

namespace App\Modules\Projects\Application\UseCases\Query;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\DTOs\ProjectData;
use App\Modules\Projects\Application\Support\ProjectDataMapper;

class ShowPublishedProjectAction
{
    public function __construct(
        private ProjectDataMapper $projectDataMapper,
    ) {}

    public function execute(Project $project): ProjectData
    {
        abort_if(! $project->is_published, 404, 'Proyecto no encontrado.');

        return $this->projectDataMapper->forApiShow($project);
    }
}
