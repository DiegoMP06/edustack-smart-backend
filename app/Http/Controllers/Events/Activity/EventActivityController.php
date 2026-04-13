<?php

namespace App\Http\Controllers\Events\Activity;

use App\Enums\Events\BehaviorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventActivityRequest;
use App\Http\Requests\Events\UpdateEventActivityRequest;
use App\Http\Resources\Events\EventActivityCollection;
use App\Http\Resources\Events\EventCollection;
use App\Models\Events\DifficultyLevel;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityCategory;
use App\Models\Events\EventActivityType;
use App\Concerns\ApiQueryable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EventActivityController extends Controller
{
    use ApiQueryable;

    private function ensureActivityBelongsToEvent(Event $event, EventActivity $activity): void
    {
        abort_if($activity->event_id !== $event->id, 404);
    }

    private function formData(): array
    {
        return [
            'difficultyLevels' => DifficultyLevel::orderBy('order')->get(['id', 'name', 'slug', 'color']),
            'activityTypes' => EventActivityType::orderBy('order')->get(['id', 'name', 'slug', 'behavior_type', 'icon']),
            'categories' => EventActivityCategory::orderBy('order')->get(['id', 'name', 'slug', 'color']),
        ];
    }

    private function validateBusinessRules(Event $event, array $data): void
    {
        $activityStart = Carbon::parse($data['started_at']);
        $activityEnd = Carbon::parse($data['ended_at']);
        $eventStart = Carbon::parse($event->start_date)->startOfDay();
        $eventEnd = Carbon::parse($event->end_date)->endOfDay();

        if ($activityStart->lt($eventStart) || $activityEnd->gt($eventEnd)) {
            throw ValidationException::withMessages([
                'started_at' => 'Las fechas de la actividad deben estar dentro del rango del evento.',
            ]);
        }
    }

    public function index(Request $request, Event $event)
    {
        $activities = $this->buildQuery(
            $event->activities(),
            defaultIncludes: ['categories', 'difficultyLevel', 'status', 'type', 'media']
        )
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('events/activities/activities', [
            'event' => (new EventCollection([$event->load(['status', 'media', 'author'])]))->first(),
            'activities' => new EventActivityCollection($activities),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create(Event $event)
    {
        return Inertia::render('events/activities/create-activity', [
            ...$this->formData(),
            'event' => (new EventCollection([$event->load(['status', 'media'])]))->first(),
        ]);
    }

    public function store(StoreEventActivityRequest $request, Event $event)
    {
        $data = $request->validated();
        $type = EventActivityType::find($data['event_activity_type_id']);
        $data['is_competition'] = $type->behavior_type === BehaviorType::COMPETITION;

        $this->validateBusinessRules($event, $data);


        $activity = $event->activities()->create([
            ...$data,

            'content' => [],
            'speakers' => isset($data['speakers']) ? $data['speakers'] : [],
            'event_status_id' => 1,
        ]);

        $activity->categories()->sync($data['categories']);

        foreach ($data['images'] as $key) {
            $activity->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }

        return redirect()->intended(
            route('events.activities.content.edit', ['event' => $event, 'activity' => $activity, 'edit' => false], false)
        )->with('message', 'Actividad creada correctamente.');
    }

    public function show(Event $event, EventActivity $activity, Request $request)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        return Inertia::render('events/activities/show-activity', [
            'event' => (new EventCollection([$event->load(['status', 'media', 'author'])]))->first(),
            'activity' => (new EventActivityCollection([$activity->load(['categories', 'difficultyLevel', 'status', 'type', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function edit(Event $event, EventActivity $activity, Request $request)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        return Inertia::render('events/activities/edit-activity', [
            ...$this->formData(),
            'event' => (new EventCollection([$event->load(['status', 'media'])]))->first(),
            'activity' => (new EventActivityCollection([$activity->load(['categories', 'difficultyLevel', 'status', 'type', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateEventActivityRequest $request, Event $event, EventActivity $activity)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        $data = $request->validated();
        $type = EventActivityType::find($data['event_activity_type_id']);
        $data['is_competition'] = $type->behavior_type === BehaviorType::COMPETITION;

        $this->validateBusinessRules($event, $data);

        if (!$data['is_competition']) {
            $rounds = $activity->rounds()->count();

            if ($rounds > 0) {
                throw ValidationException::withMessages([
                    'event_activity_type_id' => 'La actividad debe ser de tipo competencia por que tiene rondas asociadas.',
                ]);
            }
        }

        $activity->update([
            ...$data,
            'speakers' => isset($data['speakers']) ? $data['speakers'] : [],
        ]);
        $activity->categories()->sync($data['categories'] ?? []);

        return back()->with('message', 'Actividad actualizada correctamente.');
    }

    public function destroy(Event $event, EventActivity $activity)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        $activity->delete();

        return back()->with('message', 'Actividad eliminada correctamente.');
    }
}
