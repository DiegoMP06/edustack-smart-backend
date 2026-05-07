<?php

namespace App\Modules\Blog\DTOs;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

readonly class PostMediaDeletionData
{
    public function __construct(
        public Media $media,
    ) {}
}
