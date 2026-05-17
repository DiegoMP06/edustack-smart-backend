<?php

namespace App\Modules\Events\DTOs;

use App\Models\Events\Event;
use App\Modules\Admin\DTOs\UserData;
use App\Modules\Events\DTOs\EventActivity\EventActivityData;
use App\Modules\Media\DTOs\MediaData;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class EventData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public array $content,
        public float $price,
        public float $percent_off,
        public ?int $capacity,
        public bool $is_online,
        public ?string $online_link,
        public ?string $location,
        public ?float $lat,
        public ?float $lng,
        #[TypeScriptType('string')]
        public readonly CarbonImmutable $registration_started_at,
        #[TypeScriptType('string')]
        public readonly CarbonImmutable $registration_ended_at,
        #[TypeScriptType('string')]
        public CarbonImmutable $start_date,
        #[TypeScriptType('string')]
        public CarbonImmutable $end_date,
        public bool $is_published,
        public int $event_status_id,
        public int $user_id,
        #[TypeScriptType('string')]
        public ?DateTimeInterface $published_at,
        #[TypeScriptType('string')]
        public ?DateTimeInterface $updated_at,
        #[TypeScriptType('UserData|null')]
        public Lazy|UserData|null $author,
        #[DataCollectionOf(EventActivityData::class)]
        #[TypeScriptType('Array<EventActivityData>|null')]
        public Lazy|DataCollection|null $activities,
        #[TypeScriptType('EventStatusData|null')]
        public Lazy|EventStatusData|null $status,
        #[DataCollectionOf(EventCollaboratorData::class)]
        #[TypeScriptType('Array<EventCollaboratorData>|null')]
        public Lazy|DataCollection|null $collaborators,
        #[DataCollectionOf(EventRegistrationData::class)]
        #[TypeScriptType('Array<EventRegistrationData>|null')]
        public Lazy|DataCollection|null $registrations,
        /** @var array<MediaData> */
        #[DataCollectionOf(MediaData::class)]
        #[TypeScriptType('Array<MediaData>|null')]
        public Lazy|DataCollection|null $media = null,
    ) {}

    public static function fromModel(Event $event): self
    {
        return new self(
            id: $event->id,
            name: $event->name,
            slug: $event->slug,
            description: $event->description,
            content: $event->content,
            price: $event->price,
            percent_off: $event->percent_off,
            capacity: $event->capacity,
            is_online: $event->is_online,
            online_link: $event->online_link,
            location: $event->location,
            lat: $event->lat,
            lng: $event->lng,
            registration_started_at: $event->registration_started_at->toImmutable(),
            registration_ended_at: $event->registration_ended_at->toImmutable(),
            start_date: $event->start_date->toImmutable(),
            end_date: $event->end_date->toImmutable(),
            is_published: $event->is_published,
            event_status_id: $event->event_status_id,
            user_id: $event->user_id,
            published_at: $event->published_at,
            updated_at: $event->updated_at,
            author: Lazy::create(fn () => UserData::from($event->author)),
            activities: Lazy::create(fn () => EventActivityData::collect($event->activities)),
            status: Lazy::create(fn () => EventStatusData::from($event->status)),
            collaborators: Lazy::create(fn () => EventCollaboratorData::collect($event->collaborators)),
            registrations: Lazy::create(fn () => EventRegistrationData::collect($event->registrations)),
            media: Lazy::create(fn () => MediaData::collect(
                $event->getMedia('logo')->map(fn ($m) => MediaData::fromModel(
                    $m,
                    'main',
                    [
                        'main' => ['width' => 1080, 'height' => 1080],
                        'thumbnail' => ['width' => 500, 'height' => 500],
                    ],
                    ['thumbnail']
                ))
            )),
        );
    }
}
