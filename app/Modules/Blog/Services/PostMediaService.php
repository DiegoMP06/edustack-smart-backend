<?php

namespace App\Modules\Blog\Services;

use App\Models\Blog\Post;
use App\Modules\Blog\Actions\DeletePostMediaAction;
use App\Modules\Blog\Actions\StorePostMediaAction;
use App\Modules\Blog\DTOs\PostMediaData;
use App\Modules\Blog\DTOs\PostMediaDeletionData;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostMediaService
{
    public function __construct(
        private StorePostMediaAction $storeMediaAction,
        private DeletePostMediaAction $deleteMediaAction,
    ) {}

    public function store(Post $post, PostMediaData $data): void
    {
        $this->storeMediaAction->execute($post, $data);
    }

    public function destroy(Post $post, Media $media): void
    {
        $this->deleteMediaAction->execute(
            $post,
            new PostMediaDeletionData($media),
        );
    }
}
