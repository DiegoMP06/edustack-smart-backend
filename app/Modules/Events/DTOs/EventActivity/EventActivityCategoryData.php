<?php

namespace App\Modules\Events\DTOs\EventActivity;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EventActivityCategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        public string $color,
        public int $order
    ) {}
}
