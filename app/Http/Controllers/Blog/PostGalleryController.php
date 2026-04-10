<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostGalleryController extends Controller
{
    public function store(StoreModelMediaRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validated();

        foreach ($data['images'] as $key) {
            $post->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }

        return back()->with('message', 'Post actualizado correctamente.');
    }

    public function destroy(Request $request, Post $post, Media $media)
    {
        $this->authorize('update', $post);

        abort_if($media->model_type !== Post::class || $media->model_id !== $post->id, 404);

        if ($post->media()->count() == 1) {
            throw ValidationException::withMessages([
                'image' => 'El post debe tener al menos una imagen.',
            ]);
        }

        $media->delete();

        return back()->with('message', 'Post actualizado correctamente.');
    }
}
