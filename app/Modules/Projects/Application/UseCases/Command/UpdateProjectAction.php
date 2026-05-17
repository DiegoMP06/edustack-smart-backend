<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\DraftProjectFormData;
use Illuminate\Support\Facades\DB;

class UpdateProjectAction
{
    public function execute(Project $project, DraftProjectFormData $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $project->update([
                'name' => $data->name,
                'description' => $data->description,
                'repository_url' => $data->repository_url,
                'demo_url' => $data->demo_url,
                'tech_stack' => $data->tech_stack,
                'version' => $data->version,
                'license' => $data->license,
                'project_status_id' => $data->project_status_id,
            ]);

            $project->categories()->sync($data->categories);

            return $project;
        });
    }
}
