<?php

namespace App\Modules\Events\Actions;

use App\Models\Events\Event;
use App\Modules\Events\DTOs\DraftEventFormData;
use Illuminate\Support\Facades\DB;

class UpdateEventAction
{
    /**
     * Update an existing model using DTO data.
     */
    public function execute(Event $event, DraftEventFormData $data): Event
    {
        $isOutsideDateRange = $event->activities()->where(
            fn ($q) => $q->where('started_at', '<', $data->start_date)
                ->orWhere('ended_at', '>', $data->end_date)
        )->exists();

        abort_if($isOutsideDateRange, 422, 'Las fechas del evento no pueden estar fuera del rango de fechas de las actividades.');

        return DB::transaction(function () use ($event, $data) {
            $event->update([
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
            ]);

            if ($data->logo) {
                $event->clearMediaCollection('logo');
                $event->addMediaFromDisk($data->logo, 's3')
                    ->toMediaCollection('logo');
            }

            return $event;
        });
    }
}
