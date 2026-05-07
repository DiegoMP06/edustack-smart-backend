<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostData;
use Illuminate\Support\Facades\DB;

class CreatePostAction
{
    /**
     * Persist a new model using DTO data.
     */
    public function execute(PostData $data, int $userId): Post
    {
        return DB::transaction(function () use ($userId) {
            $post = Post::create([
                // Map DTO properties to model attributes.
                'user_id' => $userId,
            ]);

            // Example: $post->addMedia($data->file)->toMediaCollection('default');

            return $post;
        });
    }
}
