<?php

namespace App\Enums\Events;

enum TeamStatus: string
{
    case FORMING = 'forming';
    case CONFIRMED = 'confirmed';
    case DISQUALIFIED = 'disqualified';
}
