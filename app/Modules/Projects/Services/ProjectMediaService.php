<?php

namespace App\Modules\Projects\Services;

use App\Models\Projects\Project;
use App\Modules\Projects\Actions\DeleteProjectMediaAction;
use App\Modules\Projects\Actions\StoreProjectMediaAction;
use App\Modules\Projects\DTOs\ProjectMediaData;
use App\Modules\Projects\DTOs\ProjectMediaDeletionData;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectMediaService
{
    public function __construct(
        private StoreProjectMediaAction $storeMediaAction,
        private DeleteProjectMediaAction $deleteMediaAction,
    ) {}

    public function store(Project $project, ProjectMediaData $data): void
    {
        $this->storeMediaAction->execute($project, $data);
    }

    public function destroy(Project $project, Media $media): void
    {
        $this->deleteMediaAction->execute(
            $project,
            new ProjectMediaDeletionData($media),
        );
    }
}
