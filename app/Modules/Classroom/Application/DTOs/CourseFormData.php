<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseFormData extends Data
{
    public function __construct(
        public string $name,
        public string $summary,
        public ?string $code,
        public int $credits,
        public ?string $period,
        public float $price,
        public bool $is_free,
        public ?int $capacity,
        public int $course_status_id,
        public ?int $course_category_id,
        public string $start_date,
        public string $end_date,
        public ?string $enrollment_start_date,
        public ?string $enrollment_end_date,
        public bool $is_published,
    ) {}
}
