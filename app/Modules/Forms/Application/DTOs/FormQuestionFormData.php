<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormQuestionFormData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public string $question_type,
        public bool $is_required,
        public bool $is_visible,
        public int $order,
        public ?array $settings,
        public bool $has_correct_answer,
        public float $score,
        public ?string $explanation,
        public ?int $form_section_id,
    ) {}
}
