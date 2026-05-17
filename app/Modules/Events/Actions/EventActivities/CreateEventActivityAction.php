<?php

namespace App\Modules\Events\Actions\EventActivities;

use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Modules\Events\DTOs\EventActivity\DraftEventActivityFormData;
use Illuminate\Support\Facades\DB;

class CreateEventActivityAction
{
    /**
     * Execute the action.
     */
    public function execute(Event $event, DraftEventActivityFormData $data): EventActivity
    {
        return DB::transaction(function () use ($event, $data) {
            $activity = $event->activities()->create([
                'name' => $data->name,
                'description' => $data->description,
                'requirements' => $data->requirements,
                'is_online' => $data->is_online,
                'online_link' => $data->is_online ? $data->online_link : null,
                'location' => $data->is_online ? null : $data->location,
                'lat' => $data->is_online ? null : $data->lat,
                'lng' => $data->is_online ? null : $data->lng,
                'has_teams' => $data->has_teams,
                'requires_team' => $data->has_teams ? $data->requires_team : false,
                'max_team_size' => $data->has_teams ? $data->max_team_size : null,
                'min_team_size' => $data->has_teams ? $data->min_team_size : null,
                'capacity' => $data->with_capacity ? $data->capacity : null,
                'only_students' => $data->only_students,
                'repository_url' => $data->repository_url,
                'is_free' => $data->is_free,
                'price' => $data->price,
                'started_at' => $data->started_at,
                'ended_at' => $data->ended_at,

            ]);

            return $activity;
        });
    }
}
