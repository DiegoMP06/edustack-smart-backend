<?php

namespace App\Modules\Projects\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProjectStatusData extends Data
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
