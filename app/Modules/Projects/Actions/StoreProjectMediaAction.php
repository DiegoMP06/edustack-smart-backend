<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectMediaData;

class StoreProjectMediaAction
{
    public function execute(Project $project, ProjectMediaData $data): void
    {
        foreach ($data->images as $key) {
            $project->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }
    }
}
