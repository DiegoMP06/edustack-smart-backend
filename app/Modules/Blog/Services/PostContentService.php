<?php

namespace App\Modules\Blog\Services;

use App\Models\Blog\Post;
use App\Modules\Blog\Actions\UpdatePostContentAction;
use App\Modules\Blog\DTOs\PostContentData;

class PostContentService
{
    public function __construct(
        private UpdatePostContentAction $updateContentAction,
    ) {}

    public function update(Post $post, PostContentData $data): Post
    {
        return $this->updateContentAction->execute($post, $data);
    }
}
