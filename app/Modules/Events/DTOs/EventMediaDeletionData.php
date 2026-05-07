<?php

namespace App\Modules\Events\DTOs;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

readonly class EventMediaDeletionData
{
    public function __construct(
        public Media $media,
    ) {}
}
