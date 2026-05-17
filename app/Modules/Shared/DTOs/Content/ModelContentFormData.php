<?php

namespace App\Modules\Shared\DTOs\Content;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ModelContentFormData extends Data
{
    public function __construct(
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public array $content,
    ) {}
}
