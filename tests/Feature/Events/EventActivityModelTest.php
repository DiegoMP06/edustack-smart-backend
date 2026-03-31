<?php

use App\Models\Events\DifficultyLevel;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityCategory;
use App\Models\Events\EventActivityType;
use App\Models\Events\EventStatus;
use App\Models\User;
use Illuminate\Support\Carbon;

test('event activity exposes requested traits, relations and scopes', function () {
    $owner = User::factory()->create();

    $status = EventStatus::create([
        'name' => 'Open',
        'color' => '#22c55e',
        'description' => 'Open status',
        'order' => 1,
    ]);

    $difficulty = DifficultyLevel::create([
        'name' => 'Intermediate',
        'color' => '#f59e0b',
        'description' => 'Intermediate level',
        'order' => 1,
    ]);

    $workshopType = EventActivityType::create([
        'name' => 'Workshop',
        'description' => 'Workshop type',
        'icon' => 'wrench-screwdriver',
        'behavior_type' => 'workshop',
        'order' => 1,
    ]);

    $competitionType = EventActivityType::create([
        'name' => 'Competition',
        'description' => 'Competition type',
        'icon' => 'trophy',
        'behavior_type' => 'competition',
        'order' => 2,
    ]);

    $category = EventActivityCategory::create([
        'name' => 'AI',
        'description' => 'Artificial intelligence',
        'color' => '#3b82f6',
        'order' => 1,
    ]);

    $event = Event::create([
        'name' => 'Spring Hack Week',
        'summary' => 'Event summary',
        'content' => [],
        'price' => 0,
        'percent_off' => 0,
        'capacity' => 100,
        'is_online' => false,
        'online_link' => null,
        'location' => 'Campus',
        'lat' => null,
        'lng' => null,
        'registration_started_at' => Carbon::now()->subWeek(),
        'registration_ended_at' => Carbon::now()->addWeek(),
        'start_date' => Carbon::now()->addWeek()->toDateString(),
        'end_date' => Carbon::now()->addWeeks(2)->toDateString(),
        'is_published' => true,
        'event_status_id' => $status->id,
        'user_id' => $owner->id,
    ]);

    $workshopActivity = EventActivity::create([
        'name' => 'Laravel Workshop',
        'summary' => 'Activity summary',
        'content' => [],
        'requirements' => null,
        'is_online' => false,
        'online_link' => null,
        'location' => 'Room 101',
        'lat' => null,
        'lng' => null,
        'has_teams' => false,
        'requires_team' => false,
        'min_team_size' => 1,
        'max_team_size' => null,
        'max_participants' => 30,
        'only_students' => true,
        'is_competition' => false,
        'price' => 0,
        'speakers' => [],
        'course_id' => null,
        'project_id' => null,
        'repository_url' => null,
        'is_published' => true,
        'difficulty_level_id' => $difficulty->id,
        'event_status_id' => $status->id,
        'started_at' => Carbon::now()->addWeek(),
        'ended_at' => Carbon::now()->addWeek()->addHours(2),
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDays(5),
        'event_id' => $event->id,
        'event_activity_type_id' => $workshopType->id,
    ]);

    $workshopActivity->categories()->attach($category->id);

    $competitionActivity = EventActivity::create([
        'name' => 'Code Battle',
        'summary' => 'Competition summary',
        'content' => [],
        'requirements' => null,
        'is_online' => true,
        'online_link' => 'https://example.com/meet',
        'location' => null,
        'lat' => null,
        'lng' => null,
        'has_teams' => true,
        'requires_team' => true,
        'min_team_size' => 2,
        'max_team_size' => 4,
        'max_participants' => 40,
        'only_students' => false,
        'is_competition' => true,
        'price' => 0,
        'speakers' => [],
        'course_id' => null,
        'project_id' => null,
        'repository_url' => null,
        'is_published' => false,
        'difficulty_level_id' => $difficulty->id,
        'event_status_id' => $status->id,
        'started_at' => Carbon::now()->addDays(10),
        'ended_at' => Carbon::now()->addDays(10)->addHours(2),
        'registration_started_at' => Carbon::now()->addDay(),
        'registration_ended_at' => Carbon::now()->addDays(9),
        'event_id' => $event->id,
        'event_activity_type_id' => $competitionType->id,
    ]);

    $mediaCollections = $workshopActivity->getRegisteredMediaCollections()->pluck('name');
    $formAttachmentsRelation = $workshopActivity->formAttachments();

    expect($workshopActivity->event->is($event))->toBeTrue()
        ->and($workshopActivity->status->is($status))->toBeTrue()
        ->and($workshopActivity->difficulty->is($difficulty))->toBeTrue()
        ->and($workshopActivity->type->is($workshopType))->toBeTrue()
        ->and($workshopActivity->categories()->count())->toBe(1)
        ->and(EventActivity::published()->count())->toBe(1)
        ->and(EventActivity::competitions()->count())->toBe(1)
        ->and(EventActivity::byBehavior('workshop')->count())->toBe(1)
        ->and(EventActivity::byBehavior('competition')->count())->toBe(1)
        ->and($mediaCollections->contains('screenshots'))->toBeTrue()
        ->and($formAttachmentsRelation->getMorphType())->toBe('formable_type')
        ->and($formAttachmentsRelation->getForeignKeyName())->toBe('formable_id')
        ->and($competitionActivity->is_competition)->toBeTrue();
});
