<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostData;
use Illuminate\Support\Facades\DB;

class UpdatePostAction
{
    /**
     * Update an existing model using DTO data.
     */
    public function execute(Post $post, PostData $data): Post
    {
        return DB::transaction(function () use ($post) {
            $post->update([
                // Map DTO properties to model attributes.
            ]);

            return $post->load([]);
        });
    }
}
