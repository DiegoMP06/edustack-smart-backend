<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormResponseFormData extends Data
{
    /**
     * @param  FormResponseAnswerFormData[]  $answers
     */
    public function __construct(
        public array $answers,
        public ?string $respondent_email = null,
        public ?int $attempt_number = null,
        public ?string $ip_address = null,
        public ?string $user_agent = null,
    ) {}
}
