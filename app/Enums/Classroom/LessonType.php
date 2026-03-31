<?php

namespace App\Enums\Classroom;

enum LessonType: string
{
    case TEXT = 'text';
    case VIDEO = 'video';
    case ACTIVITY = 'activity';
    case LIVE = 'live';
}
