<?php

namespace App\Modules\Forms\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FormSectionFormData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public int $order,
        public bool $is_visible,
    ) {}
}
