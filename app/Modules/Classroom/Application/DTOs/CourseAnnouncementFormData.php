<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseAnnouncementFormData extends Data
{
    public function __construct(
        public string $title,
        public array $content,
        public bool $is_pinned,
        public bool $notify_students,
        public ?string $published_at,
    ) {}
}
