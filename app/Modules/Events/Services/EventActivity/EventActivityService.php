<?php

namespace App\Modules\Events\Services\EventActivity;

use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Modules\Events\DTOs\EventActivity\EventActivityData;
use App\Modules\Events\Queries\ListEventActivitiesQuery;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class EventActivityService
{
    public function __construct(
        private ListEventActivitiesQuery $listEventActivitiesQuery
    ) {}

    public function listEventActivities(ListCollectionQueryParamsData $data, Event $event): LengthAwarePaginator
    {
        $activities = $this->listEventActivitiesQuery->paginate(
            params: ['event_id' => $event->id],
            perPage: $data->per_page,
            defaultIncludes: ['user'],
        );

        $activities->getCollection()->transform(
            fn (EventActivity $activity) => EventActivityData::fromModel($activity)
                ->include('user')
        );

        return $activities;
    }
}
