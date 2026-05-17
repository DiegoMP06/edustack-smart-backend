<?php

namespace App\Modules\Blog\Application\UseCases\Command;

use App\Models\Blog\Post;
use App\Modules\Media\Domain\Contracts\GalleryRepository;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeletePostMediaAction
{
    public function __construct(private GalleryRepository $galleryRepository) {}

    public function execute(Post $post, Media $media): void
    {
        // Regla de negocio: el post debe conservar al menos 1 imagen.
        // Vive aquí (Application) no en Infrastructure.
        if ($post->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'El post debe tener al menos una imagen.',
            ]);
        }

        $this->galleryRepository->deleteFromCollection($post, $media);
    }
}
