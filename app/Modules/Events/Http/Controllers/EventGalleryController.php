<?php

namespace App\Modules\Events\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventMediaData;
use App\Modules\Events\Services\EventMediaService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventGalleryController extends Controller
{
    public function __construct(
        private EventMediaService $mediaService,
    ) {}

    /**
     * Add uploaded media keys to the model collection.
     */
    public function store(StoreModelMediaRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $data = EventMediaData::fromArray($request->validated());
        $this->mediaService->store($event, $data);

        return back()->with('message', 'Event media updated successfully.');
    }

    /**
     * Remove media from the model collection.
     */
    public function destroy(Event $event, Media $media)
    {
        $this->authorize('update', $event);

        $this->mediaService->destroy($event, $media);

        return back()->with('message', 'Event media updated successfully.');
    }
}
