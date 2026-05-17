<?php

namespace App\Modules\Blog\Application\UseCases\Command;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\DTOs\DraftPostFormData;
use App\Modules\Blog\Domain\Contracts\PostWriteRepository;

class UpdatePostAction
{
    public function __construct(private PostWriteRepository $postWriteRepository) {}

    public function execute(Post $post, DraftPostFormData $data): Post
    {
        return $this->postWriteRepository->update($post, $data);
    }
}
