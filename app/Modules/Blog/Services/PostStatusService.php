<?php

namespace App\Modules\Blog\Services;

use App\Models\Blog\Post;
use App\Modules\Blog\Actions\TogglePostStatusAction;
use App\Modules\Blog\DTOs\PostStatusData;

class PostStatusService
{
    public function __construct(
        private TogglePostStatusAction $toggleStatusAction,
    ) {}

    public function toggle(Post $post): Post
    {
        $data = PostStatusData::fromModel($post);

        return $this->toggleStatusAction->execute($post, $data);
    }
}
