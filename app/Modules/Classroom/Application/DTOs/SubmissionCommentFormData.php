<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class SubmissionCommentFormData extends Data
{
    public function __construct(
        public string $content,
    ) {}
}
