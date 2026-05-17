<?php

namespace App\Enums\Auth;

enum UserRole: string
{
    case GUEST = 'guest';
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case MEMBER = 'member';
    case ADMIN = 'admin';
}
