<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectMediaDeletionData;
use Illuminate\Validation\ValidationException;

class DeleteProjectMediaAction
{
    public function execute(Project $project, ProjectMediaDeletionData $data): void
    {
        $media = $data->media;

        abort_if($media->model_type !== Project::class || $media->model_id !== $project->id, 404);

        if ($project->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'At least one image is required.',
            ]);
        }

        $media->delete();
    }
}
