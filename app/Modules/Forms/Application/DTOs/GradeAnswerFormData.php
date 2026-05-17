<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GradeAnswerFormData extends Data
{
    public function __construct(
        public float $score_awarded,
        public ?bool $is_correct = null,
        public ?string $feedback = null,
    ) {}
}
