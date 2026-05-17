<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseDiscussionFormData extends Data
{
    public function __construct(
        public string $title,
        public array $content,
        public bool $is_pinned,
        public ?int $course_lesson_id,
    ) {}
}
