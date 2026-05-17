<?php

namespace App\Modules\Events\DTOs;

use DateTimeInterface;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DraftEventFormData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        #[LiteralTypeScriptType('File[]')]
        public ?string $logo,
        public ?string $location,
        /** @var array<string, float> */
        #[LiteralTypeScriptType('{lat: number, lng: number}')]
        public ?array $latLng,
        #[Optional]
        public float $lat,
        #[Optional]
        public float $lng,
        public bool $is_free,
        public float $price,
        public float $percent_off,
        public bool $is_online,
        public ?string $online_link,
        public bool $with_capacity,
        public ?int $capacity,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $start_date,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $end_date,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $registration_started_at,
        #[LiteralTypeScriptType('Date')]
        public DateTimeInterface $registration_ended_at,
    ) {}
}
