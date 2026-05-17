<?php

namespace App\Modules\Events\DTOs\EventActivity;

use DateTimeInterface;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class DraftEventActivityFormData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        public ?string $requirements,
        #[Optional]
        #[LiteralTypeScriptType('File[]')]
        public array $images,
        public bool $is_online,
        public ?string $online_link,
        public ?string $location,
        /** @var array<string, float> */
        #[LiteralTypeScriptType('{lat: number, lng: number}')]
        public ?array $latLng,
        #[Optional]
        public float $lat,
        #[Optional]
        public float $lng,
        public bool $has_teams,
        public bool $requires_team,
        public ?int $min_team_size,
        public ?int $max_team_size,
        public bool $with_capacity,
        public ?int $capacity,
        public bool $only_students,
        public bool $is_free,
        public float $price,
        /** @var array<SpeakerData> */
        #[TypeScriptType('Array<SpeakerData>')]
        #[DataCollectionOf(SpeakerData::class)]
        public array $speakers,
        public ?string $repository_url,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $started_at,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $ended_at,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $registration_started_at,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $registration_ended_at,
        public int $event_activity_type_id,
        public int $difficulty_level_id,
        #[TypeScriptType('Array<int>')]
        public array $categories,
    ) {}
}
