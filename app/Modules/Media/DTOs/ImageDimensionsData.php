<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ImageDimensionsData extends Data
{
    public function __construct(
        public int $width,
        public int $height,
    ) {}
}
