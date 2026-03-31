<?php

namespace App\Enums\Events;

enum EventRegistrationStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case WAITLISTED = 'waitlisted';
    case CANCELLED = 'cancelled';
}
