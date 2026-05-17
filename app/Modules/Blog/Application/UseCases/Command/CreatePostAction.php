<?php

namespace App\Modules\Blog\Application\UseCases\Command;

use App\Models\Blog\Post;
use App\Models\User;
use App\Modules\Blog\Application\DTOs\DraftPostFormData;
use App\Modules\Blog\Domain\Contracts\PostWriteRepository;

class CreatePostAction
{
    public function __construct(private PostWriteRepository $postWriteRepository) {}

    public function execute(DraftPostFormData $data, User $user): Post
    {
        return $this->postWriteRepository->createForUser($user, $data);
    }
}
