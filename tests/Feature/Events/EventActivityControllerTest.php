<?php

use App\Enums\Events\BehaviorType;
use App\Http\Middleware\ActiveAccount;
use App\Models\Events\DifficultyLevel;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityCategory;
use App\Models\Events\EventActivityType;
use App\Models\Events\EventStatus;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Carbon;
use Spatie\Permission\Middleware\RoleMiddleware;

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        EnsureEmailIsVerified::class,
        ActiveAccount::class,
        RoleMiddleware::class,
    ]);
});

function activityPayload(array $overrides = []): array
{
    return [
        'name' => 'Laravel Activity',
        'summary' => str_repeat('Resumen actividad ', 4),
        'event_status_id' => 1,
        'event_activity_type_id' => 1,
        'difficulty_level_id' => null,
        'started_at' => Carbon::now()->addDays(1)->toDateTimeString(),
        'ended_at' => Carbon::now()->addDays(1)->addHour()->toDateTimeString(),
        'registration_started_at' => Carbon::now()->addHours(2)->toDateTimeString(),
        'registration_ended_at' => Carbon::now()->addHours(6)->toDateTimeString(),
        'price' => 0,
        'capacity' => 30,
        'is_online' => false,
        'online_link' => null,
        'location' => 'Room 101',
        'lat' => 19.4326,
        'lng' => -99.1332,
        'has_teams' => false,
        'requires_team' => false,
        'min_team_size' => 1,
        'max_team_size' => null,
        'only_students' => true,
        'is_competition' => false,
        'course_id' => null,
        'project_id' => null,
        'repository_url' => null,
        'categories' => [],
        ...$overrides,
    ];
}

test('store validates activity range is inside event dates', function () {
    $owner = User::factory()->create();
    $status = EventStatus::create(['name' => 'Open', 'color' => '#22c55e', 'description' => 'Open', 'order' => 1]);
    $type = EventActivityType::create(['name' => 'Workshop', 'description' => 'x', 'icon' => 'wrench', 'behavior_type' => BehaviorType::WORKSHOP, 'order' => 1]);

    $event = Event::create([
        'name' => 'Main Event',
        'summary' => 'Event summary',
        'content' => [],
        'price' => 0,
        'percent_off' => 0,
        'capacity' => 100,
        'is_online' => false,
        'location' => 'Campus',
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
        'start_date' => Carbon::now()->addDays(2)->toDateString(),
        'end_date' => Carbon::now()->addDays(3)->toDateString(),
        'is_published' => true,
        'event_status_id' => $status->id,
        'user_id' => $owner->id,
    ]);

    $response = $this->post(route('events.activities.store', ['event' => $event], false), activityPayload([
        'event_status_id' => $status->id,
        'event_activity_type_id' => $type->id,
        'started_at' => Carbon::now()->addDay()->toDateTimeString(),
        'ended_at' => Carbon::now()->addDay()->addHour()->toDateTimeString(),
    ]));

    $response->assertSessionHasErrors('started_at');
});

test('store validates competition activities use competition behavior', function () {
    $owner = User::factory()->create();
    $status = EventStatus::create(['name' => 'Open', 'color' => '#22c55e', 'description' => 'Open', 'order' => 1]);
    $type = EventActivityType::create(['name' => 'Workshop', 'description' => 'x', 'icon' => 'wrench', 'behavior_type' => BehaviorType::WORKSHOP, 'order' => 1]);

    $event = Event::create([
        'name' => 'Main Event',
        'summary' => 'Event summary',
        'content' => [],
        'price' => 0,
        'percent_off' => 0,
        'capacity' => 100,
        'is_online' => false,
        'location' => 'Campus',
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
        'start_date' => Carbon::now()->addDays(1)->toDateString(),
        'end_date' => Carbon::now()->addDays(2)->toDateString(),
        'is_published' => true,
        'event_status_id' => $status->id,
        'user_id' => $owner->id,
    ]);

    $response = $this->post(route('events.activities.store', ['event' => $event], false), activityPayload([
        'event_status_id' => $status->id,
        'event_activity_type_id' => $type->id,
        'is_competition' => true,
        'started_at' => Carbon::now()->addDays(1)->addHour()->toDateTimeString(),
        'ended_at' => Carbon::now()->addDays(1)->addHours(2)->toDateTimeString(),
        'registration_started_at' => Carbon::now()->subHour()->toDateTimeString(),
        'registration_ended_at' => Carbon::now()->toDateTimeString(),
    ]));

    $response->assertSessionHasErrors('event_activity_type_id');
});

test('store and update sync categories', function () {
    $owner = User::factory()->create();
    $status = EventStatus::create(['name' => 'Open', 'color' => '#22c55e', 'description' => 'Open', 'order' => 1]);
    $type = EventActivityType::create(['name' => 'Competition', 'description' => 'x', 'icon' => 'trophy', 'behavior_type' => BehaviorType::COMPETITION, 'order' => 1]);
    $difficulty = DifficultyLevel::create(['name' => 'Inter', 'color' => '#f59e0b', 'description' => 'Level', 'order' => 1]);
    $categoryA = EventActivityCategory::create(['name' => 'AI', 'description' => 'A', 'color' => '#3b82f6', 'order' => 1]);
    $categoryB = EventActivityCategory::create(['name' => 'Backend', 'description' => 'B', 'color' => '#14b8a6', 'order' => 2]);

    $event = Event::create([
        'name' => 'Main Event',
        'summary' => 'Event summary',
        'content' => [],
        'price' => 0,
        'percent_off' => 0,
        'capacity' => 100,
        'is_online' => false,
        'location' => 'Campus',
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
        'start_date' => Carbon::now()->addDays(1)->toDateString(),
        'end_date' => Carbon::now()->addDays(2)->toDateString(),
        'is_published' => true,
        'event_status_id' => $status->id,
        'user_id' => $owner->id,
    ]);

    $store = $this->post(route('events.activities.store', ['event' => $event], false), activityPayload([
        'event_status_id' => $status->id,
        'event_activity_type_id' => $type->id,
        'difficulty_level_id' => $difficulty->id,
        'is_competition' => true,
        'categories' => [$categoryA->id],
        'started_at' => Carbon::now()->addDays(1)->addHour()->toDateTimeString(),
        'ended_at' => Carbon::now()->addDays(1)->addHours(2)->toDateTimeString(),
        'registration_started_at' => Carbon::now()->subHour()->toDateTimeString(),
        'registration_ended_at' => Carbon::now()->toDateTimeString(),
    ]));

    $store->assertRedirect();
    $activity = EventActivity::query()->firstOrFail();
    expect($activity->categories()->pluck('event_activity_categories.id')->all())->toBe([$categoryA->id]);

    $update = $this->put(route('events.activities.update', ['event' => $event, 'activity' => $activity], false), activityPayload([
        'name' => 'Updated Activity',
        'event_status_id' => $status->id,
        'event_activity_type_id' => $type->id,
        'difficulty_level_id' => $difficulty->id,
        'is_competition' => true,
        'categories' => [$categoryB->id],
        'started_at' => Carbon::now()->addDays(1)->addHours(3)->toDateTimeString(),
        'ended_at' => Carbon::now()->addDays(1)->addHours(4)->toDateTimeString(),
        'registration_started_at' => Carbon::now()->subHour()->toDateTimeString(),
        'registration_ended_at' => Carbon::now()->toDateTimeString(),
    ]));

    $update->assertSessionHasNoErrors();
    $activity->refresh();
    expect($activity->categories()->pluck('event_activity_categories.id')->all())->toBe([$categoryB->id]);
});
