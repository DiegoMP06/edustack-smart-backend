<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use Illuminate\Support\Facades\DB;

class DeletePostAction
{
    /**
     * Delete the model in a transaction.
     */
    public function execute(Post $post): void
    {
        DB::transaction(function () use ($post) {
            // Example: $post->clearMediaCollection();
            $post->deleteOrFail();
        });
    }
}
