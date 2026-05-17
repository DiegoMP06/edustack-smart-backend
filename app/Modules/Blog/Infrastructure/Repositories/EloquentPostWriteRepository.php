<?php

namespace App\Modules\Blog\Infrastructure\Repositories;

use App\Models\Blog\Post;
use App\Models\User;
use App\Modules\Blog\Application\DTOs\DraftPostFormData;
use App\Modules\Blog\Domain\Contracts\PostWriteRepository;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;
use Illuminate\Support\Facades\DB;

class EloquentPostWriteRepository implements PostWriteRepository
{
    public function createForUser(User $user, DraftPostFormData $data): Post
    {
        return DB::transaction(function () use ($user, $data) {
            $post = $user->posts()->create([
                'name' => $data->name,
                'description' => $data->description,
                'reading_time_minutes' => $data->reading_time_minutes,
                'post_type_id' => $data->post_type_id,
                'content' => [],
            ]);

            $post->categories()->sync($data->categories);

            return $post;
        });
    }

    public function update(Post $post, DraftPostFormData $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $post->update([
                'name' => $data->name,
                'description' => $data->description,
                'reading_time_minutes' => $data->reading_time_minutes,
                'post_type_id' => $data->post_type_id,
            ]);

            $post->categories()->sync($data->categories);

            return $post;
        });
    }

    public function delete(Post $post): void
    {
        $post->deleteOrFail();
    }

    public function updateContent(Post $post, ModelContentFormData $data): Post
    {
        $post->content = $data->content;
        $post->save();

        return $post;
    }

    public function togglePublished(Post $post): Post
    {
        $post->is_published = ! $post->is_published;
        $post->published_at = $post->is_published ? now() : null;
        $post->save();

        return $post;
    }
}
