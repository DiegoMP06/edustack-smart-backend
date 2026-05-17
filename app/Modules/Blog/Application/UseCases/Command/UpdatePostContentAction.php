<?php

namespace App\Modules\Blog\Application\UseCases\Command;

use App\Models\Blog\Post;
use App\Modules\Blog\Domain\Contracts\PostWriteRepository;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class UpdatePostContentAction
{
    public function __construct(private PostWriteRepository $postWriteRepository) {}

    public function execute(Post $post, ModelContentFormData $data): Post
    {
        return $this->postWriteRepository->updateContent($post, $data);
    }
}
