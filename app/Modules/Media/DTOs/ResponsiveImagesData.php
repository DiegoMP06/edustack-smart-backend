<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ResponsiveImagesData extends Data
{
    public function __construct(
        public ?string $xl,
        public ?string $lg,
        public ?string $base,
        public ?string $md,
        public ?string $sm,
        public ?string $xs,
    ) {}
}
