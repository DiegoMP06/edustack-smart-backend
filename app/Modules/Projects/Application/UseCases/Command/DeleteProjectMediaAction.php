<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeleteProjectMediaAction
{
    public function execute(Project $project, Media $media): void
    {
        abort_if($media->model_type !== Project::class || $media->model_id !== $project->id, 404);

        if ($project->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'El proyecto debe tener al menos una imagen.',
            ]);
        }

        $media->delete();
    }
}
