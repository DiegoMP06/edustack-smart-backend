<?php

namespace App\Enums\Events;

enum EventStatusSlug: string
{
    case UPCOMING = 'upcoming';
    case OPEN = 'open';
    case CLOSED = 'closed';
    case ONGOING = 'ongoing';
    case FINISHED = 'finished';
}
