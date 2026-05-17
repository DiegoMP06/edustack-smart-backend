<?php

namespace App\Modules\Blog\Application\UseCases\Command;

use App\Models\Blog\Post;
use App\Modules\Blog\Domain\Contracts\PostWriteRepository;

class TogglePostStatusAction
{
    public function __construct(private PostWriteRepository $postWriteRepository) {}

    public function execute(Post $post): Post
    {
        return $this->postWriteRepository->togglePublished($post);
    }
}
