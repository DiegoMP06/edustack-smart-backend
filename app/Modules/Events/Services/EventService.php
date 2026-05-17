<?php

namespace App\Modules\Events\Services;

use App\Models\Events\Event;
use App\Models\User;
use App\Modules\Events\DTOs\EventData;
use App\Modules\Events\Queries\ListUserEventsQuery;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class EventService
{
    public function __construct(
        private ListUserEventsQuery $listEventsQuery,
    ) {}

    public function listUserEvents(ListCollectionQueryParamsData $data, User $user): LengthAwarePaginator
    {
        $events = $this->listEventsQuery->paginate(
            params: ['user_id' => $user->id],
            perPage: $data->per_page,
            defaultIncludes: ['status', 'media'],
        );

        $events->getCollection()->transform(
            fn (Event $event) => EventData::fromModel($event)
                ->include('status', 'media')
        );

        return $events;
    }
}
