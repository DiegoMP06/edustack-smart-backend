<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GeneratePresignedURLItemData extends Data
{
    public function __construct(
        public int $id,
        public string $extension,
        public string $type,
    ) {}
}
