<?php

namespace App\Modules\Events\DTOs\EventActivity;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SocialMediaSpeakerData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $url,
    ) {}
}
