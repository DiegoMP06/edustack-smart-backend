<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class AssignmentSubmissionFormData extends Data
{
    public function __construct(
        public ?string $text_content,
        public ?string $url_content,
    ) {}
}
