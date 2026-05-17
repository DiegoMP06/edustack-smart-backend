<?php

namespace App\Modules\Events\DTOs\EventActivity;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class SpeakerData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $father_last_name,
        public string $mother_last_name,
        public string $email,
        public ?string $job_title,
        public ?string $company,
        /** @var array<SocialMediaSpeakerData> */
        #[TypeScriptType('Array<SocialMediaSpeakerData>')]
        #[DataCollectionOf(SocialMediaSpeakerData::class)]
        public array $social,
        public string $biography,
    ) {}
}
