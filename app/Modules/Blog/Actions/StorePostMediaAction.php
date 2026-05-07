<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostMediaData;

class StorePostMediaAction
{
    public function execute(Post $post, PostMediaData $data): void
    {
        foreach ($data->images as $key) {
            $post->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }
    }
}
