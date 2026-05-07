<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostStatusData;

class TogglePostStatusAction
{
    public function execute(Post $post, PostStatusData $data): Post
    {
        $post->is_published = $data->isActive;
        $post->published_at = $data->isActive ? now() : null;
        $post->save();

        return $post;
    }
}
