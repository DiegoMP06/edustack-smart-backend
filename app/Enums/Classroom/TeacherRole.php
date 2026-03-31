<?php

namespace App\Enums\Classroom;

enum TeacherRole: string
{
    case CO_TEACHER = 'co_teacher';
    case ASSISTANT = 'assistant';
    case GUEST = 'guest';
}
