<?php

namespace App\Enums\Events;

enum EventActivityTypeSlug: string
{
    case WORKSHOP = 'workshop';
    case LECTURE = 'lecture';
    case COMPETITION = 'competition';
    case SEMINAR = 'seminar';
    case COURSE = 'course';
    case PROJECT = 'project';
}
