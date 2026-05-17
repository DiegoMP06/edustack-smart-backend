<?php

namespace App\Modules\Events\Rules\EventActivity;

use App\Models\Events\Event;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActivityWithinEventRange implements ValidationRule
{
    public function __construct(
        private readonly Event $event,
        private readonly string $pairedDate
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        [$start, $end] = $attribute === 'started_at'
            ? [$value, $this->pairedDate]
            : [$this->pairedDate, $value];

        $eventStart = Carbon::parse($this->event->start_date)->startOfDay();
        $eventEnd = Carbon::parse($this->event->end_date)->endOfDay();

        if (Carbon::parse($start)->lt($eventStart) || Carbon::parse($end)->gt($eventEnd)) {
            $fail(
                $attribute === 'started_at'
                ? 'El inicio de la actividad debe estar dentro del rango del evento.'
                : 'El fin de la actividad debe estar dentro del rango del evento.'
            );
        }
    }
}
