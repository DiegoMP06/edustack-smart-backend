<?php

namespace App\Modules\Media\Infrastructure\Repositories;

use App\Modules\Media\Domain\Contracts\GalleryRepository;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpatieS3GalleryRepository implements GalleryRepository
{
    public function addFromS3(HasMedia $model, string $collection, array $s3Keys): void
    {
        foreach ($s3Keys as $key) {
            $model->addMediaFromDisk($key, 's3')
                ->toMediaCollection($collection);
        }
    }

    public function deleteFromCollection(HasMedia $model, Media $media): void
    {
        /** @var Model $model */
        abort_if(
            $media->model_type !== get_class($model) || $media->model_id !== $model->getKey(),
            404,
            'El media no pertenece a este modelo.'
        );

        $media->delete();
    }
}
