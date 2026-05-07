<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectData;
use Illuminate\Support\Facades\DB;

class UpdateProjectAction
{
    /**
     * Update an existing model using DTO data.
     */
    public function execute(Project $project, ProjectData $data): Project
    {
        return DB::transaction(function () use ($project) {
            $project->update([
                // Map DTO properties to model attributes.
            ]);

            return $project->load([]);
        });
    }
}
