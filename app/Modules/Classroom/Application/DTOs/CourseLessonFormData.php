<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseLessonFormData extends Data
{
    public function __construct(
        public string $name,
        public ?string $summary,
        public string $type,
        public ?string $video_url,
        public ?int $video_duration_seconds,
        public int $order,
        public int $estimated_minutes,
        public bool $is_published,
        public bool $is_preview,
        public int $course_section_id,
    ) {}
}
