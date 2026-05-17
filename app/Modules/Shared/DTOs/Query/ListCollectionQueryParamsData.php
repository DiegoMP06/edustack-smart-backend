<?php

namespace App\Modules\Shared\DTOs\Query;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ListCollectionQueryParamsData extends Data
{
    public function __construct(
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public ?array $filter,
        public ?int $per_page,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            filter: $request->query('filter') ?? [],
            per_page: $request->query('per_page') ?? 20,
        );
    }
}
