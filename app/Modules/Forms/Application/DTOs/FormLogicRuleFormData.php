<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormLogicRuleFormData extends Data
{
    public function __construct(
        public ?string $name,
        public string $action_type,
        public ?int $target_question_id,
        public ?int $target_section_id,
        public string $condition_operator,
        public int $order,
        public bool $is_active,
    ) {}
}
