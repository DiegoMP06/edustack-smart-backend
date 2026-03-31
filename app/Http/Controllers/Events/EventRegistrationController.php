<?php

namespace App\Http\Controllers\Events;

use App\Enums\Events\EventRegistrationStatus;
use App\Enums\Payments\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventRegistrationRequest;
use App\Models\Events\Event;
use App\Models\Events\EventRegistration;
use App\Models\Payments\Payment;
use Illuminate\Validation\ValidationException;

class EventRegistrationController extends Controller
{
    public function store(StoreEventRegistrationRequest $request, Event $event)
    {
        $user = $request->user();

        if (
            ! $event->registration_started_at
            || ! $event->registration_ended_at
            || now()->lt($event->registration_started_at)
            || now()->gt($event->registration_ended_at)
        ) {
            throw ValidationException::withMessages([
                'event' => 'Las inscripciones no están abiertas.',
            ]);
        }

        $exists = $event->registrations()
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'event' => 'Ya estás inscrito a este evento.',
            ]);
        }

        if ($event->capacity !== null) {
            $confirmed = $event->registrations()->where('status', EventRegistrationStatus::CONFIRMED)->count();
            $status = $confirmed >= $event->capacity
                ? EventRegistrationStatus::WAITLISTED
                : EventRegistrationStatus::PENDING;
        } else {
            $status = EventRegistrationStatus::PENDING;
        }

        $registration = $event->registrations()->create([
            'user_id' => $user->id,
            'status' => $status,
        ]);

        if ((float) $event->price > 0) {
            $year = now()->year;
            $count = Payment::whereYear('created_at', $year)->count() + 1;
            $ref = 'PAY-'.$year.'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);

            $registration->payable()->create([
                'reference_code' => $ref,
                'qr_payload' => encrypt($ref),
                'amount' => (float) $event->price,
                'discount' => (float) $event->price * ((float) $event->percent_off / 100),
                'total' => (float) $event->price - ((float) $event->price * ((float) $event->percent_off / 100)),
                'currency' => 'MXN',
                'status' => PaymentStatus::PENDING,
                'user_id' => $user->id,
            ]);
        }

        return back()->with('message', 'Inscripción registrada correctamente.');
    }

    public function destroy(Event $event, EventRegistration $registration)
    {
        abort_if($registration->event_id !== $event->id, 404);

        $registration->update(['status' => EventRegistrationStatus::CANCELLED]);

        if ($registration->payment && $registration->payment->status === PaymentStatus::PENDING) {
            $registration->payment->update(['status' => PaymentStatus::EXPIRED]);
        }

        return back()->with('message', 'Inscripción cancelada correctamente.');
    }
}
