<?php

namespace App\Enums\Events;

enum ActivityRegistrationStatus: string
{
    case REGISTERED = 'registered';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case DISQUALIFIED = 'disqualified';
}
