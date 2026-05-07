<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetPostAction
{
    /**
     * Retrieve a model by its primary key.
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id): Post
    {
        $post = Post::find($id);

        if (! $post) {
            throw new ModelNotFoundException("The record with ID {$id} was not found.");
        }

        // If you prefer returning a DTO, map it here and adjust the return type.

        return $post;
    }
}
