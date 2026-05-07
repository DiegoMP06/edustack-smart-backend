<?php

namespace App\Modules\Projects\DTOs;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

readonly class ProjectMediaDeletionData
{
    public function __construct(
        public Media $media,
    ) {}
}
