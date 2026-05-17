<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseDiscussionReplyFormData extends Data
{
    public function __construct(
        public array $content,
        public ?int $parent_id,
        public bool $is_solution = false,
    ) {}
}
