<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class ModelMediaFormData extends Data
{
    public function __construct(
        #[TypeScriptType('Array<string>')]
        public array $images,
    ) {}
}
