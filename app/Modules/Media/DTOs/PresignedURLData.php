<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PresignedURLData extends Data
{
    public function __construct(
        public int $id,
        public string $path,
        public string $url
    ) {}
}
