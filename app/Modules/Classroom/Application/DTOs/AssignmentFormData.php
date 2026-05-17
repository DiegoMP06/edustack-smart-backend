<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class AssignmentFormData extends Data
{
    public function __construct(
        public string $name,
        public ?string $summary,
        public array $instructions,
        public float $max_score,
        public float $passing_score,
        public bool $allow_late_submissions,
        public int $max_attempts,
        public string $submission_type,
        public bool $is_published,
        public ?string $due_date,
        public ?string $available_from,
        public ?int $course_lesson_id,
    ) {}
}
