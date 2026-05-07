<?php

namespace App\Modules\Blog\DTOs;

use App\Models\Blog\Post;

readonly class PostStatusData
{
    public function __construct(
        public bool $isActive,
    ) {}

    public static function fromModel(Post $post): self
    {
        return new self(
            isActive: ! $post->is_published,
        );
    }
}
