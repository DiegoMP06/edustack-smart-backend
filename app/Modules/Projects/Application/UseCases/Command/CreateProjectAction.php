<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use App\Models\User;
use App\Modules\Projects\DTOs\DraftProjectFormData;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    public function execute(DraftProjectFormData $data, User $user): Project
    {
        return DB::transaction(function () use ($data, $user) {
            $project = $user->projects()->create([
                'name' => $data->name,
                'description' => $data->description,
                'repository_url' => $data->repository_url,
                'demo_url' => $data->demo_url,
                'tech_stack' => $data->tech_stack,
                'version' => $data->version,
                'license' => $data->license,
                'project_status_id' => $data->project_status_id,
                'content' => [],
            ]);

            $project->categories()->sync($data->categories);

            foreach ($data->images as $key) {
                $project->addMediaFromDisk($key, 's3')
                    ->toMediaCollection('screenshots');
            }

            return $project;
        });
    }
}
