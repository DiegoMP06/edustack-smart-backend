<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Models\User;
use App\Modules\Events\DTOs\DraftEventFormData;
use Illuminate\Support\Facades\DB;

class CreateEventAction
{
    /**
     * Persist a new model using DTO data.
     */
    public function execute(DraftEventFormData $data, User $user): Event
    {
        return DB::transaction(function () use ($data, $user) {
            $event = $user->events()->create([
                'name' => $data->name,
                'description' => $data->description,
                'price' => $data->price,
                'percent_off' => $data->percent_off,
                'capacity' => $data->with_capacity ? $data->capacity : null,
                'is_online' => $data->is_online,
                'online_link' => $data->is_online ? $data->online_link : null,
                'location' => $data->is_online ? null : $data->location,
                'lat' => $data->is_online ? null : $data->lat,
                'lng' => $data->is_online ? null : $data->lng,
                'registration_started_at' => $data->registration_started_at,
                'registration_ended_at' => $data->registration_ended_at,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'content' => [],
                'event_status_id' => 1,
            ]);

            $event->addMediaFromDisk($data->logo, 's3')
                ->toMediaCollection('logo');

            return $event;
        });
    }
}
