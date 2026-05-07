<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostContentData;

class UpdatePostContentAction
{
    public function execute(Post $post, PostContentData $data): Post
    {
        $post->content = $data->content;
        $post->save();

        return $post;
    }
}
