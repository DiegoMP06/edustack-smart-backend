<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DraftFormFormData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public int $form_type_id,
        public bool $requires_login,
        public bool $allow_multiple_responses,
        public ?int $max_responses,
        public bool $collect_email,
        public bool $show_progress_bar,
        public bool $shuffle_sections,
        public ?string $available_from,
        public ?string $available_until,
        public ?string $confirmation_message,
        public ?string $redirect_url,
        public bool $is_quiz_mode,
        public ?int $time_limit_minutes,
        public int $max_attempts,
        public ?float $passing_score,
        public bool $randomize_questions,
        public bool $randomize_options,
        public string $show_results_to_respondent,
        public bool $show_correct_answers,
        public bool $show_feedback_after,
    ) {}
}
