<?php

namespace App\Http\Controllers\Events\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventActivityGalleryController extends Controller
{
    public function store(StoreModelMediaRequest $request, Event $event, EventActivity $activity)
    {
        $this->authorize('update', $activity);

        $data = $request->validated();

        foreach ($data['images'] as $key) {
            $activity->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }

        return back()->with('message', 'Actividad actualizada correctamente.');
    }

    public function destroy(Request $request, Event $event, EventActivity $activity, Media $media)
    {
        $this->authorize('update', $activity);

        abort_if($media->model_type !== EventActivity::class || $media->model_id !== $activity->id, 404);

        if ($activity->media()->count() == 1) {
            throw ValidationException::withMessages([
                'image' => 'La actividad debe tener al menos una imagen.',
            ]);
        }

        $media->delete();

        return back()->with('message', 'Actividad actualizado correctamente.');
    }
}
