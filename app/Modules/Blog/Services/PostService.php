<?php

namespace App\Modules\Blog\Services;

use App\Models\Blog\Post;
use App\Modules\Blog\Actions\CreatePostAction;
use App\Modules\Blog\Actions\DeletePostAction;
use App\Modules\Blog\Actions\UpdatePostAction;
use App\Modules\Blog\DTOs\PostData;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public function __construct(
        private CreatePostAction $createAction,
        private UpdatePostAction $updateAction,
        private DeletePostAction $deleteAction,
    ) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        return Post::query()
            ->with([])
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where('title', 'like', "%{$value}%"))
            ->latest()
            ->paginate(15);
    }

    public function findOrFail(int $id): Post
    {
        return Post::with([])->findOrFail($id);
    }

    public function create(PostData $data, int $userId): Post
    {
        return $this->createAction->execute($data, $userId);
    }

    public function update(Post $post, PostData $data): Post
    {
        return $this->updateAction->execute($post, $data);
    }

    public function delete(Post $post): void
    {
        $this->deleteAction->execute($post);
    }
}
