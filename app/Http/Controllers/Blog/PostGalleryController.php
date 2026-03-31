<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StorePostGalleryRequest;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostGalleryController extends Controller
{
    public function store(StorePostGalleryRequest $request, Post $post)
    {
        $data = $request->validated();

        $images = $request->file('images');

        foreach ($images as $file) {
            $post->addMedia($file)
                ->toMediaCollection('gallery');
        }

        return back()->with('message', 'Post actualizado correctamente.');
    }

    public function destroy(Request $request, Post $post, Media $media)
    {
        if ($post->media()->count() == 1) {
            throw ValidationException::withMessages([
                'image' => 'El post debe tener al menos una imagen.',
            ]);
        }

        $media->delete();

        return back()->with('message', 'Post actualizado correctamente.');
    }
}
