<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormResponseAnswerFormData extends Data
{
    public function __construct(
        public int $form_question_id,
        public bool $was_skipped,
        public ?string $text_answer = null,
        public ?float $number_answer = null,
        public ?string $date_answer = null,
        public ?string $time_answer = null,
        public ?string $datetime_answer = null,
        public ?array $selected_option_ids = null,
        public ?array $structured_answer = null,
        public ?float $score = null,
        public ?bool $is_correct = null,
        public ?string $feedback = null,
    ) {}
}
