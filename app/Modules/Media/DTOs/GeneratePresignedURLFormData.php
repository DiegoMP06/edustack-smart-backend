<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class GeneratePresignedURLFormData extends Data
{
    public function __construct(
        /** @var array<GeneratePresignedURLItemData> */
        #[DataCollectionOf(GeneratePresignedURLItemData::class)]
        #[TypeScriptType('Array<GeneratePresignedURLItemData>')]
        public array $images,
    ) {}
}
