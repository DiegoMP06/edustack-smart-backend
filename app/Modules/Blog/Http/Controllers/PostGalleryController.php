<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\UseCases\Command\DeletePostMediaAction;
use App\Modules\Blog\Application\UseCases\Command\StorePostMediaAction;
use App\Modules\Media\DTOs\ModelMediaFormData;
use App\Modules\Media\Http\Requests\StoreModelMediaRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostGalleryController extends Controller
{
    /**
     * Add uploaded media keys to the model collection.
     */
    public function store(
        StoreModelMediaRequest $request,
        Post $post,
        StorePostMediaAction $action,
    ): RedirectResponse {
        $this->authorize('update', $post);

        $data = ModelMediaFormData::from($request->validated());
        $action->execute($post, $data);

        return back()->with('message', 'Galería actualizada correctamente.');
    }

    /**
     * Remove media from the model collection.
     */
    public function destroy(
        Post $post,
        Media $media,
        DeletePostMediaAction $action,
    ): RedirectResponse {
        $this->authorize('update', $post);

        $action->execute($post, $media);

        return back()->with('message', 'Galería actualizada correctamente.');
    }
}
