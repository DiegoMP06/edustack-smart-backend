<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use App\Modules\Media\DTOs\ModelMediaFormData;

class StoreProjectMediaAction
{
    public function execute(Project $project, ModelMediaFormData $data): void
    {
        foreach ($data->images as $key) {
            $project->addMediaFromDisk($key, 's3')
                ->toMediaCollection('screenshots');
        }
    }
}
