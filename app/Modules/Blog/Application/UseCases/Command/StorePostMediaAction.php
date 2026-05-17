<?php

namespace App\Modules\Blog\Application\UseCases\Command;

use App\Models\Blog\Post;
use App\Modules\Media\Domain\Contracts\GalleryRepository;
use App\Modules\Media\DTOs\ModelMediaFormData;

class StorePostMediaAction
{
    public function __construct(private GalleryRepository $galleryRepository) {}

    public function execute(Post $post, ModelMediaFormData $data): void
    {
        $this->galleryRepository->addFromS3($post, 'gallery', $data->images);
    }
}
