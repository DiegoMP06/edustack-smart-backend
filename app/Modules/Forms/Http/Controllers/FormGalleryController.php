<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormMediaData;
use App\Modules\Forms\Services\FormMediaService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FormGalleryController extends Controller
{
    public function __construct(
        private FormMediaService $mediaService,
    ) {}

    /**
     * Add uploaded media keys to the model collection.
     */
    public function store(StoreModelMediaRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $data = FormMediaData::fromArray($request->validated());
        $this->mediaService->store($form, $data);

        return back()->with('message', 'Form media updated successfully.');
    }

    /**
     * Remove media from the model collection.
     */
    public function destroy(Form $form, Media $media)
    {
        $this->authorize('update', $form);

        $this->mediaService->destroy($form, $media);

        return back()->with('message', 'Form media updated successfully.');
    }
}
