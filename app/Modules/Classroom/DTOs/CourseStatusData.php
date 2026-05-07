<?php

namespace App\Modules\Classroom\DTOs;

use App\Models\Classroom\Course;

readonly class CourseStatusData
{
    public function __construct(
        public bool $isActive,
    ) {}

    public static function fromModel(Course $course): self
    {
        return new self(
            isActive: ! $course->is_published,
        );
    }
}
