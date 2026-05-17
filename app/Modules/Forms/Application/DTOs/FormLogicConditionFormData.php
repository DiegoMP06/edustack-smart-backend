<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormLogicConditionFormData extends Data
{
    public function __construct(
        public int $source_question_id,
        public string $operator,
        public ?array $comparison_value,
        public ?int $comparison_option_id,
    ) {}
}
