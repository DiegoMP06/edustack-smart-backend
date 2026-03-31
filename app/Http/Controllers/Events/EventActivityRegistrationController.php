<?php

namespace App\Http\Controllers\Events;

use App\Enums\Events\ActivityRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityRegistration;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventActivityRegistrationController extends Controller
{
    private function ensureActivityBelongsToEvent(Event $event, EventActivity $activity): void
    {
        abort_if($activity->event_id !== $event->id, 404);
    }

    public function store(Request $request, Event $event, EventActivity $activity)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);
        $user = $request->user();

        if ($activity->only_students && ! $user->hasRole('student')) {
            throw ValidationException::withMessages([
                'activity' => 'Esta actividad es solo para estudiantes.',
            ]);
        }

        if ($activity->registration_started_at !== null) {
            if (now()->lt($activity->registration_started_at) || ($activity->registration_ended_at && now()->gt($activity->registration_ended_at))) {
                throw ValidationException::withMessages([
                    'activity' => 'Las inscripciones a esta actividad no están abiertas.',
                ]);
            }
        }

        if ($activity->max_participants !== null) {
            $count = $activity->registrations()
                ->whereIn('status', [ActivityRegistrationStatus::REGISTERED, ActivityRegistrationStatus::CONFIRMED])
                ->count();

            if ($count >= $activity->max_participants) {
                throw ValidationException::withMessages([
                    'activity' => 'Esta actividad ya alcanzó su cupo máximo.',
                ]);
            }
        }

        $exists = EventActivityRegistration::query()
            ->where('user_id', $user->id)
            ->where('event_activity_id', $activity->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'activity' => 'Ya estás inscrito a esta actividad.',
            ]);
        }

        $activity->registrations()->create([
            'user_id' => $user->id,
            'status' => ActivityRegistrationStatus::REGISTERED,
        ]);

        $message = $activity->requires_team
            ? 'Inscrito correctamente. Recuerda unirte o crear un equipo.'
            : 'Inscrito correctamente.';

        return back()->with('message', $message);
    }

    public function destroy(Event $event, EventActivity $activity, EventActivityRegistration $registration)
    {
        $this->ensureActivityBelongsToEvent($event, $activity);
        abort_if($registration->event_activity_id !== $activity->id, 404);

        $registration->update(['status' => ActivityRegistrationStatus::CANCELLED]);

        return back()->with('message', 'Inscripción cancelada correctamente.');
    }
}
