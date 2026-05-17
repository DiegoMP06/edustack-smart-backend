<?php

namespace App\Modules\Events\DTOs\EventActivity;

use App\Enums\Events\BehaviorType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EventActivityTypeData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        public string $icon,
        public BehaviorType $behavior_type,
        public int $order
    ) {}
}
