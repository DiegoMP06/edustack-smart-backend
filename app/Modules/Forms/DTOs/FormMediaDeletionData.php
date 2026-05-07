<?php

namespace App\Modules\Forms\DTOs;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

readonly class FormMediaDeletionData
{
    public function __construct(
        public Media $media,
    ) {}
}
