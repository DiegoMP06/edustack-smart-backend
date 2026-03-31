<?php

use App\Enums\Events\RoundStatus;
use App\Enums\Events\TeamMemberRole;
use App\Enums\Events\TeamStatus;
use App\Http\Middleware\ActiveAccount;
use App\Models\Events\CompetitionRound;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityTeam;
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

function eventAndCompetitionActivity(array $activityOverrides = []): array
{
    $owner = User::factory()->create();
    $status = EventStatus::create(['name' => 'Open', 'color' => '#22c55e', 'description' => 'Open', 'order' => 1]);

    $event = Event::create([
        'name' => 'Competition Event',
        'summary' => 'Event summary',
        'content' => [],
        'price' => 0,
        'percent_off' => 0,
        'capacity' => 100,
        'is_online' => false,
        'location' => 'Campus',
        'registration_started_at' => Carbon::now()->subWeek(),
        'registration_ended_at' => Carbon::now()->addWeek(),
        'start_date' => Carbon::now()->addDays(7)->toDateString(),
        'end_date' => Carbon::now()->addDays(8)->toDateString(),
        'is_published' => true,
        'event_status_id' => $status->id,
        'user_id' => $owner->id,
    ]);

    $activity = EventActivity::create([
        'name' => 'Competition Activity',
        'summary' => str_repeat('Resumen ', 10),
        'content' => [],
        'requirements' => null,
        'is_online' => false,
        'online_link' => null,
        'location' => 'Aula 2',
        'lat' => null,
        'lng' => null,
        'has_teams' => true,
        'requires_team' => false,
        'min_team_size' => 1,
        'max_team_size' => 3,
        'max_participants' => 20,
        'only_students' => false,
        'is_competition' => true,
        'price' => 0,
        'speakers' => [],
        'course_id' => null,
        'project_id' => null,
        'repository_url' => null,
        'is_published' => true,
        'difficulty_level_id' => null,
        'event_status_id' => $status->id,
        'started_at' => Carbon::now()->addDays(2),
        'ended_at' => Carbon::now()->addDays(2)->addHours(2),
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
        'event_id' => $event->id,
        'event_activity_type_id' => null,
        ...$activityOverrides,
    ]);

    return [$event, $activity];
}

test('captain cannot leave when team has other members', function () {
    [$event, $activity] = eventAndCompetitionActivity();
    $captain = User::factory()->create();
    $member = User::factory()->create();

    $team = EventActivityTeam::create([
        'name' => 'Team Alpha',
        'captain_user_id' => $captain->id,
        'status' => TeamStatus::FORMING,
        'event_activity_id' => $activity->id,
    ]);

    $team->members()->create(['user_id' => $captain->id, 'role' => TeamMemberRole::CAPTAIN]);
    $team->members()->create(['user_id' => $member->id, 'role' => TeamMemberRole::MEMBER]);

    $response = $this->actingAs($captain)
        ->post(route('events.activities.teams.leave', ['event' => $event, 'activity' => $activity, 'team' => $team], false));

    $response->assertSessionHasErrors('team');
});

test('captain as only member leaves and team is soft deleted', function () {
    [$event, $activity] = eventAndCompetitionActivity();
    $captain = User::factory()->create();

    $team = EventActivityTeam::create([
        'name' => 'Solo Team',
        'captain_user_id' => $captain->id,
        'status' => TeamStatus::FORMING,
        'event_activity_id' => $activity->id,
    ]);

    $team->members()->create(['user_id' => $captain->id, 'role' => TeamMemberRole::CAPTAIN]);

    $response = $this->actingAs($captain)
        ->post(route('events.activities.teams.leave', ['event' => $event, 'activity' => $activity, 'team' => $team], false));

    $response->assertRedirect();
    expect(EventActivityTeam::withTrashed()->find($team->id)?->deleted_at)->not->toBeNull();
});

test('round number is auto assigned as max plus one', function () {
    [$event, $activity] = eventAndCompetitionActivity();

    CompetitionRound::create([
        'name' => 'Round 1',
        'content' => ['rules' => 'A'],
        'round_number' => 1,
        'participants_per_round' => 10,
        'starting_from_scratch' => false,
        'qualified_participants' => 5,
        'winners_count' => 1,
        'is_the_final' => false,
        'status' => RoundStatus::PENDING,
        'started_at' => Carbon::now()->addDays(2),
        'ended_at' => Carbon::now()->addDays(2)->addHour(),
        'event_activity_id' => $activity->id,
    ]);

    $response = $this->post(route('events.activities.rounds.store', ['event' => $event, 'activity' => $activity], false), [
        'name' => 'Round 2',
        'content' => ['rules' => 'B'],
        'participants_per_round' => 10,
        'starting_from_scratch' => false,
        'qualified_participants' => 5,
        'winners_count' => 1,
        'is_the_final' => false,
        'started_at' => Carbon::now()->addDays(3)->toDateTimeString(),
        'ended_at' => Carbon::now()->addDays(3)->addHour()->toDateTimeString(),
    ]);

    $response->assertRedirect();
    expect(CompetitionRound::where('name', 'Round 2')->firstOrFail()->round_number)->toBe(2);
});

test('round creation requires competition activity', function () {
    [$event, $activity] = eventAndCompetitionActivity(['is_competition' => false]);

    $response = $this->post(route('events.activities.rounds.store', ['event' => $event, 'activity' => $activity], false), [
        'name' => 'Round X',
        'content' => ['rules' => 'X'],
        'participants_per_round' => 10,
        'starting_from_scratch' => false,
        'qualified_participants' => 5,
        'winners_count' => 1,
        'is_the_final' => false,
        'started_at' => Carbon::now()->addDays(3)->toDateTimeString(),
        'ended_at' => Carbon::now()->addDays(3)->addHour()->toDateTimeString(),
    ]);

    $response->assertSessionHasErrors('activity');
});

test('round destroy only works when status is pending', function () {
    [$event, $activity] = eventAndCompetitionActivity();

    $activeRound = CompetitionRound::create([
        'name' => 'Active Round',
        'content' => ['rules' => 'A'],
        'round_number' => 1,
        'participants_per_round' => 10,
        'starting_from_scratch' => false,
        'qualified_participants' => 5,
        'winners_count' => 1,
        'is_the_final' => false,
        'status' => RoundStatus::ACTIVE,
        'started_at' => Carbon::now()->addDays(2),
        'ended_at' => Carbon::now()->addDays(2)->addHour(),
        'event_activity_id' => $activity->id,
    ]);

    $failed = $this->delete(route('events.activities.rounds.destroy', [
        'event' => $event,
        'activity' => $activity,
        'round' => $activeRound,
    ], false));
    $failed->assertSessionHasErrors('round');

    $pendingRound = CompetitionRound::create([
        'name' => 'Pending Round',
        'content' => ['rules' => 'B'],
        'round_number' => 2,
        'participants_per_round' => 10,
        'starting_from_scratch' => false,
        'qualified_participants' => 5,
        'winners_count' => 1,
        'is_the_final' => false,
        'status' => RoundStatus::PENDING,
        'started_at' => Carbon::now()->addDays(2),
        'ended_at' => Carbon::now()->addDays(2)->addHour(),
        'event_activity_id' => $activity->id,
    ]);

    $ok = $this->delete(route('events.activities.rounds.destroy', [
        'event' => $event,
        'activity' => $activity,
        'round' => $pendingRound,
    ], false));
    $ok->assertRedirect();

    expect(CompetitionRound::find($pendingRound->id))->toBeNull();
});
