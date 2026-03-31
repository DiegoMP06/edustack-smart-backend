<?php

use App\Enums\Events\ActivityRegistrationStatus;
use App\Enums\Events\EventRegistrationStatus;
use App\Enums\Payments\PaymentStatus;
use App\Http\Middleware\ActiveAccount;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityRegistration;
use App\Models\Events\EventRegistration;
use App\Models\Events\EventStatus;
use App\Models\Payments\Payment;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Carbon;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        EnsureEmailIsVerified::class,
        ActiveAccount::class,
        RoleMiddleware::class,
    ]);
});

function eventForRegistration(User $owner, array $overrides = []): Event
{
    $status = EventStatus::create(['name' => 'Open', 'color' => '#22c55e', 'description' => 'Open', 'order' => 1]);

    return Event::create([
        'name' => 'Summer Event',
        'summary' => 'Event summary',
        'content' => [],
        'price' => 0,
        'percent_off' => 10,
        'capacity' => 1,
        'is_online' => false,
        'location' => 'Campus',
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
        'start_date' => Carbon::now()->addDays(10)->toDateString(),
        'end_date' => Carbon::now()->addDays(12)->toDateString(),
        'is_published' => true,
        'event_status_id' => $status->id,
        'user_id' => $owner->id,
        ...$overrides,
    ]);
}

function activityForRegistration(Event $event, array $overrides = []): EventActivity
{
    return EventActivity::create([
        'name' => 'Activity',
        'summary' => str_repeat('Resumen ', 10),
        'content' => [],
        'requirements' => null,
        'is_online' => false,
        'online_link' => null,
        'location' => 'Room 1',
        'lat' => null,
        'lng' => null,
        'has_teams' => false,
        'requires_team' => false,
        'min_team_size' => 1,
        'max_team_size' => null,
        'max_participants' => 1,
        'only_students' => false,
        'is_competition' => false,
        'price' => 0,
        'speakers' => [],
        'course_id' => null,
        'project_id' => null,
        'repository_url' => null,
        'is_published' => true,
        'difficulty_level_id' => null,
        'event_status_id' => $event->event_status_id,
        'started_at' => Carbon::now()->addDays(2),
        'ended_at' => Carbon::now()->addDays(2)->addHours(2),
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
        'event_id' => $event->id,
        'event_activity_type_id' => null,
        ...$overrides,
    ]);
}

test('event registration validates open window and duplicate', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $event = eventForRegistration($owner, ['registration_started_at' => Carbon::now()->addDay()]);

    $closed = $this->actingAs($user)->post(route('events.registrations.store', ['event' => $event], false));
    $closed->assertSessionHasErrors('event');

    $event->update([
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
    ]);

    $ok = $this->actingAs($user)->post(route('events.registrations.store', ['event' => $event], false));
    $ok->assertRedirect();

    $duplicate = $this->actingAs($user)->post(route('events.registrations.store', ['event' => $event], false));
    $duplicate->assertSessionHasErrors('event');
});

test('event registration creates payable payment when event has price', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $event = eventForRegistration($owner, ['price' => 1000]);

    $response = $this->actingAs($user)->post(route('events.registrations.store', ['event' => $event], false));
    $response->assertRedirect();

    $registration = EventRegistration::where('event_id', $event->id)->where('user_id', $user->id)->firstOrFail();
    $payment = Payment::where('payable_type', EventRegistration::class)->where('payable_id', $registration->id)->firstOrFail();

    expect($payment->reference_code)->toMatch('/^PAY-\d{4}-\d{5}$/')
        ->and($payment->status)->toBe(PaymentStatus::PENDING)
        ->and($registration->status)->toBe(EventRegistrationStatus::PENDING);
});

test('event registration destroy cancels and expires pending payment', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $event = eventForRegistration($owner);

    $registration = EventRegistration::create([
        'event_id' => $event->id,
        'user_id' => $user->id,
        'status' => EventRegistrationStatus::PENDING,
    ]);

    $payment = Payment::create([
        'reference_code' => 'PAY-'.now()->year.'-00001',
        'qr_payload' => encrypt('PAY'),
        'payable_type' => EventRegistration::class,
        'payable_id' => $registration->id,
        'amount' => 100,
        'discount' => 0,
        'total' => 100,
        'currency' => 'MXN',
        'status' => PaymentStatus::PENDING,
        'user_id' => $user->id,
    ]);

    $registration->update(['payment_id' => $payment->id]);

    $response = $this->delete(route('events.registrations.destroy', ['event' => $event, 'registration' => $registration], false));
    $response->assertRedirect();

    expect($registration->fresh()->status)->toBe(EventRegistrationStatus::CANCELLED)
        ->and($payment->fresh()->status)->toBe(PaymentStatus::EXPIRED);
});

test('activity registration validates students window capacity and creates registered status', function () {
    $owner = User::factory()->create();
    $event = eventForRegistration($owner);
    $activity = activityForRegistration($event, ['only_students' => true]);
    $user = User::factory()->create();

    $notStudent = $this->actingAs($user)->post(route('events.activities.registrations.store', ['event' => $event, 'activity' => $activity], false));
    $notStudent->assertSessionHasErrors('activity');

    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
    $user->assignRole('student');

    $activity->update(['registration_started_at' => Carbon::now()->addDay()]);
    $closed = $this->actingAs($user)->post(route('events.activities.registrations.store', ['event' => $event, 'activity' => $activity], false));
    $closed->assertSessionHasErrors('activity');

    $activity->update([
        'registration_started_at' => Carbon::now()->subDay(),
        'registration_ended_at' => Carbon::now()->addDay(),
    ]);

    EventActivityRegistration::create([
        'user_id' => User::factory()->create()->id,
        'event_activity_id' => $activity->id,
        'status' => ActivityRegistrationStatus::REGISTERED,
    ]);

    $full = $this->actingAs($user)->post(route('events.activities.registrations.store', ['event' => $event, 'activity' => $activity], false));
    $full->assertSessionHasErrors('activity');

    $activity->update(['max_participants' => 2]);
    $ok = $this->actingAs($user)->post(route('events.activities.registrations.store', ['event' => $event, 'activity' => $activity], false));
    $ok->assertRedirect();

    $registration = EventActivityRegistration::where('user_id', $user->id)->where('event_activity_id', $activity->id)->firstOrFail();
    expect($registration->status)->toBe(ActivityRegistrationStatus::REGISTERED);
});

test('activity registration destroy updates status to cancelled', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $event = eventForRegistration($owner);
    $activity = activityForRegistration($event);

    $registration = EventActivityRegistration::create([
        'user_id' => $user->id,
        'event_activity_id' => $activity->id,
        'status' => ActivityRegistrationStatus::REGISTERED,
    ]);

    $response = $this->delete(route('events.activities.registrations.destroy', [
        'event' => $event,
        'activity' => $activity,
        'registration' => $registration,
    ], false));

    $response->assertRedirect();
    expect($registration->fresh()->status)->toBe(ActivityRegistrationStatus::CANCELLED);
});
