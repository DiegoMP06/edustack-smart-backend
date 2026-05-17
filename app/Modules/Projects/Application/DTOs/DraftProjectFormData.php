<?php

namespace App\Modules\Projects\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class DraftProjectFormData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        #[Optional]
        #[LiteralTypeScriptType('File[]')]
        public ?array $images,
        public string $repository_url,
        public string $demo_url,
        #[TypeScriptType('Array<string>')]
        public array $tech_stack,
        public string $version,
        public string $license,
        public int $project_status_id,
        #[TypeScriptType('Array<int>')]
        public array $categories,
    ) {}
}
