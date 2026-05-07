<?php

namespace App\Modules\Classroom\DTOs;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

readonly class CourseMediaDeletionData
{
    public function __construct(
        public Media $media,
    ) {}
}
