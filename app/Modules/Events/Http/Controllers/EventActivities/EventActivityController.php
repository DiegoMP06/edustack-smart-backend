<?php

namespace App\Modules\Events\Http\Controllers\EventActivities;

use App\Http\Resources\Events\EventActivityCollection;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Modules\Events\DTOs\EventData;
use App\Modules\Events\Http\Controllers\Controller;
use App\Modules\Events\Http\Requests\EventActivities\StoreEventActivityRequest;
use App\Modules\Events\Http\Requests\EventActivities\UpdateEventActivityRequest;
use App\Modules\Events\Services\EventActivity\EventActivityService;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\Request;
use Inertia\Response;

class EventActivityController extends Controller
{
    public function __construct(
        private readonly EventActivityService $eventActivityService
    ) {}

    public function forCreateForm(): array
    {
        return [];
    }

    public function forEditForm(): array
    {
        return $this->forCreateForm();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event): Response
    {
        $data = ListCollectionQueryParamsData::fromRequest($request);
        $activities = $this->eventActivityService->listEventActivities($data, $event);

        return inertia('events/activities/activities', [
            'event' => EventData::fromModel($event),
            'activities' => new EventActivityCollection($activities),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventActivityRequest $request): void {}

    /**
     * Display the specified resource.
     */
    public function show(EventActivity $eventActivity): void {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventActivity $eventActivity): void {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventActivityRequest $request, EventActivity $eventActivity): void {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventActivity $eventActivity): void {}
}
