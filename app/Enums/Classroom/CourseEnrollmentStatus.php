<?php

namespace App\Enums\Classroom;

enum CourseEnrollmentStatus: string
{
    case ENROLLED = 'enrolled';
    case COMPLETED = 'completed';
    case DROPPED = 'dropped';
}
