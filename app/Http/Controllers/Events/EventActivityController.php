<?php

namespace App\Http\Controllers\Events;

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
use App\Models\Events\EventStatus;
use App\Traits\ApiQueryable;
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
            'statuses' => EventStatus::orderBy('order')->get(['id', 'name', 'slug', 'color']),
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

        if ((bool) $data['is_competition'] === true) {
            $type = EventActivityType::find($data['event_activity_type_id']);

            if (
                ($type?->behavior_type instanceof BehaviorType && $type->behavior_type !== BehaviorType::COMPETITION)
                || ($type?->behavior_type instanceof BehaviorType === false && $type?->behavior_type !== BehaviorType::COMPETITION->value)
            ) {
                throw ValidationException::withMessages([
                    'event_activity_type_id' => 'El tipo de actividad debe ser de competencia.',
                ]);
            }
        }
    }

    public function index(Request $request, Event $event)
    {
        $activities = $this->buildQuery(
            $event->activities(),
            defaultIncludes: ['categories', 'difficultyLevel', 'status', 'type']
        )
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('events/activities/index', [
            ...$this->formData(),
            'event' => (new EventCollection([$event->load(['activities', 'author', 'media'])]))->first(),
            'activities' => new EventActivityCollection($activities),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create(Event $event)
    {
        return Inertia::render('events/activities/create', [
            ...$this->formData(),
            'event' => $event,
        ]);
    }

    public function store(StoreEventActivityRequest $request, Event $event)
    {
        $data = $request->validated();
        $this->validateBusinessRules($event, $data);

        if (array_key_exists('capacity', $data)) {
            $data['max_participants'] = $data['capacity'];
            unset($data['capacity']);
        }

        $activity = $event->activities()->create([
            ...$data,
            'content' => [],
        ]);

        $activity->categories()->sync($data['categories'] ?? []);

        return redirect()->intended(
            route('events.activities.content.edit', ['event' => $event, 'activity' => $activity, 'edit' => false], false)
        );
    }

    public function show(Event $event, EventActivity $activity, Request $request)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        return Inertia::render('events/activities/show', [
            'event' => $event,
            'activity' => $activity->load(['categories', 'difficultyLevel', 'status', 'type', 'media']),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function edit(Event $event, EventActivity $activity, Request $request)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        return Inertia::render('events/activities/edit', [
            ...$this->formData(),
            'event' => $event,
            'activity' => $activity->load(['categories', 'difficultyLevel', 'status', 'type', 'media']),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateEventActivityRequest $request, Event $event, EventActivity $activity)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);

        $data = $request->validated();

        $this->validateBusinessRules($event, [
            ...$data,
            'started_at' => $data['started_at'] ?? $activity->started_at,
            'ended_at' => $data['ended_at'] ?? $activity->ended_at,
            'is_competition' => $data['is_competition'] ?? $activity->is_competition,
            'event_activity_type_id' => $data['event_activity_type_id'] ?? $activity->event_activity_type_id,
        ]);

        if (array_key_exists('capacity', $data)) {
            $data['max_participants'] = $data['capacity'];
            unset($data['capacity']);
        }

        $activity->update($data);
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
