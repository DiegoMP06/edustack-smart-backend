<?php

namespace App\Modules\Blog\Domain\Contracts;

use App\Models\Blog\Post;
use App\Models\User;
use App\Modules\Blog\Application\DTOs\DraftPostFormData;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

interface PostWriteRepository
{
    public function createForUser(User $user, DraftPostFormData $data): Post;

    public function update(Post $post, DraftPostFormData $data): Post;

    public function delete(Post $post): void;

    public function updateContent(Post $post, ModelContentFormData $data): Post;

    public function togglePublished(Post $post): Post;
}
