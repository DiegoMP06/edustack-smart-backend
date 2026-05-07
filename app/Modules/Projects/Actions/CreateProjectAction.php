<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectData;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    /**
     * Persist a new model using DTO data.
     */
    public function execute(ProjectData $data, int $userId): Project
    {
        return DB::transaction(function () use ($userId) {
            $project = Project::create([
                // Map DTO properties to model attributes.
                'user_id' => $userId,
            ]);

            // Example: $project->addMedia($data->file)->toMediaCollection('default');

            return $project;
        });
    }
}
