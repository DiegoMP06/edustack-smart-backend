<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class GradeSubmissionFormData extends Data
{
    public function __construct(
        public float $score,
        public ?string $feedback,
    ) {}
}
