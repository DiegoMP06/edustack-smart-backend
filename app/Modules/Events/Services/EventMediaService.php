<?php

namespace App\Modules\Events\Services;

use App\Models\Events\Event;
use App\Modules\Events\Actions\DeleteEventMediaAction;
use App\Modules\Events\Actions\StoreEventMediaAction;
use App\Modules\Events\DTOs\EventMediaData;
use App\Modules\Events\DTOs\EventMediaDeletionData;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventMediaService
{
    public function __construct(
        private StoreEventMediaAction $storeMediaAction,
        private DeleteEventMediaAction $deleteMediaAction,
    ) {}

    public function store(Event $event, EventMediaData $data): void
    {
        $this->storeMediaAction->execute($event, $data);
    }

    public function destroy(Event $event, Media $media): void
    {
        $this->deleteMediaAction->execute(
            $event,
            new EventMediaDeletionData($media),
        );
    }
}
