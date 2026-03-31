<?php

namespace App\Enums\Projects;

enum ProjectStatusSlug: string
{
    case PLANNING = 'planning';
    case IN_PROGRESS = 'in-progress';
    case ON_HOLD = 'on-hold';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
