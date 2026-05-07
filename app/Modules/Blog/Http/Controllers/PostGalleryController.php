<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostMediaData;
use App\Modules\Blog\Services\PostMediaService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostGalleryController extends Controller
{
    public function __construct(
        private PostMediaService $mediaService,
    ) {}

    /**
     * Add uploaded media keys to the model collection.
     */
    public function store(StoreModelMediaRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = PostMediaData::fromArray($request->validated());
        $this->mediaService->store($post, $data);

        return back()->with('message', 'Post media updated successfully.');
    }

    /**
     * Remove media from the model collection.
     */
    public function destroy(Post $post, Media $media)
    {
        $this->authorize('update', $post);

        $this->mediaService->destroy($post, $media);

        return back()->with('message', 'Post media updated successfully.');
    }
}
