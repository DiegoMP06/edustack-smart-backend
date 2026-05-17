<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseTeacherFormData extends Data
{
    public function __construct(
        public int $user_id,
        public string $role,
    ) {}
}
