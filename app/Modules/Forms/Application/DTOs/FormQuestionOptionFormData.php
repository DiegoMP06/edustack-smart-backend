<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormQuestionOptionFormData extends Data
{
    public function __construct(
        public string $text,
        public ?string $value,
        public ?string $image_url,
        public int $order,
        public bool $is_row,
        public ?int $correct_order,
        public ?int $match_option_id,
        public bool $is_correct,
        public ?string $feedback,
    ) {}
}
