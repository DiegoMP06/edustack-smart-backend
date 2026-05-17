<?php

namespace App\Modules\Events\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EventStatusData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $color,
        public string $description,
        public int $order
    ) {}
}
