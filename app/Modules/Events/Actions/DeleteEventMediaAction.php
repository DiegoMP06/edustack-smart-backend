<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventMediaDeletionData;
use Illuminate\Validation\ValidationException;

class DeleteEventMediaAction
{
    public function execute(Event $event, EventMediaDeletionData $data): void
    {
        $media = $data->media;

        abort_if($media->model_type !== Event::class || $media->model_id !== $event->id, 404);

        if ($event->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'At least one image is required.',
            ]);
        }

        $media->delete();
    }
}
