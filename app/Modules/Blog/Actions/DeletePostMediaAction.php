<?php

namespace App\Modules\Blog\Actions;

use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostMediaDeletionData;
use Illuminate\Validation\ValidationException;

class DeletePostMediaAction
{
    public function execute(Post $post, PostMediaDeletionData $data): void
    {
        $media = $data->media;

        abort_if($media->model_type !== Post::class || $media->model_id !== $post->id, 404);

        if ($post->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'At least one image is required.',
            ]);
        }

        $media->delete();
    }
}
