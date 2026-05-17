<?php

namespace App\Modules\Blog\Application\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript()]
class DraftPostFormData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        #[Optional]
        #[LiteralTypeScriptType('File[]')]
        public ?array $images,
        public int $reading_time_minutes,
        public int $post_type_id,
        #[TypeScriptType('Array<int>')]
        public array $categories,
    ) {}
}
