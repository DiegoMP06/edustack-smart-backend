<?php

namespace App\Modules\Events\DTOs\EventActivity;

use App\Models\Events\EventActivity;
use App\Modules\Events\DTOs\DifficultyLevelData;
use App\Modules\Events\DTOs\EventData;
use App\Modules\Events\DTOs\EventStatusData;
use App\Modules\Media\DTOs\MediaData;
use DateTimeInterface;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class EventActivityData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public array $content,
        public string $requirements,
        public bool $is_online,
        public ?string $online_link,
        public ?string $location,
        public ?float $lat,
        public ?float $lng,
        public bool $has_teams,
        public bool $requires_team,
        public ?int $min_team_size,
        public ?int $max_team_size,
        public int $capacity,
        public bool $only_students,
        public bool $is_competition,
        public float $price,
        /** @var array<SpeakerData> */
        #[TypeScriptType('Array<SpeakerData>')]
        #[DataCollectionOf(SpeakerData::class)]
        public DataCollection $speakers,
        public ?string $repository_url,
        public bool $is_published,
        #[TypeScriptType('string')]
        public DateTimeInterface $started_at,
        #[TypeScriptType('string')]
        public DateTimeInterface $ended_at,
        #[TypeScriptType('string')]
        public DateTimeInterface $registration_started_at,
        #[TypeScriptType('string')]
        public DateTimeInterface $registration_ended_at,
        public int $difficulty_level_id,
        public int $event_status_id,
        public int $event_activity_type_id,
        public int $event_id,
        #[TypeScriptType('string')]
        public DateTimeInterface $created_at,
        #[TypeScriptType('string|null')]
        public ?DateTimeInterface $updated_at,
        #[TypeScriptType('EventData|null')]
        public Lazy|EventData|null $event = null,
        #[TypeScriptType('EventActivityTypeData|null')]
        public Lazy|EventActivityTypeData|null $type = null,
        #[TypeScriptType('EventStatusData|null')]
        public Lazy|EventStatusData|null $status = null,
        #[TypeScriptType('DifficultyLevelData|null')]
        public Lazy|DifficultyLevelData|null $difficulty = null,
        #[DataCollectionOf(EventActivityCategoryData::class)]
        #[TypeScriptType('Array<EventActivityCategoryData>|null')]
        public Lazy|DataCollection|null $categories = null,
        #[DataCollectionOf(EventActivityTeamData::class)]
        #[TypeScriptType('Array<EventActivityTeamData>|null')]
        public Lazy|DataCollection|null $teams = null,
        #[DataCollectionOf(EventActivityRegistrationData::class)]
        #[TypeScriptType('Array<EventActivityRegistrationData>|null')]
        public Lazy|DataCollection|null $registrations = null,
        /** @var array<MediaData> */
        #[DataCollectionOf(MediaData::class)]
        #[TypeScriptType('Array<MediaData>|null')]
        public Lazy|DataCollection|null $media = null,
    ) {}

    public static function fromModel(EventActivity $activity): self
    {
        return new self(
            id: $activity->id,
            name: $activity->name,
            slug: $activity->slug,
            description: $activity->description,
            content: $activity->content,
            requirements: $activity->requirements,
            is_online: $activity->is_online,
            online_link: $activity->online_link,
            location: $activity->location,
            lat: $activity->lat,
            lng: $activity->lng,
            has_teams: $activity->has_teams,
            requires_team: $activity->requires_team,
            min_team_size: $activity->min_team_size,
            max_team_size: $activity->max_team_size,
            capacity: $activity->capacity,
            only_students: $activity->only_students,
            is_competition: $activity->is_competition,
            price: $activity->price,
            speakers: SpeakerData::collect($activity->speakers),
            repository_url: $activity->repository_url,
            is_published: $activity->is_published,
            started_at: $activity->started_at,
            ended_at: $activity->ended_at,
            registration_started_at: $activity->registration_started_at,
            registration_ended_at: $activity->registration_ended_at,
            difficulty_level_id: $activity->difficulty_level_id,
            event_status_id: $activity->event_status_id,
            event_activity_type_id: $activity->event_activity_type_id,
            event_id: $activity->event_id,
            created_at: $activity->created_at,
            updated_at: $activity->updated_at,
            event: Lazy::create(fn () => EventData::fromModel($activity->event)),
            type: Lazy::create(fn () => EventActivityTypeData::from($activity->type)),
            status: Lazy::create(fn () => EventStatusData::from($activity->status)),
            difficulty: Lazy::create(fn () => DifficultyLevelData::from($activity->difficulty)),
            categories: Lazy::create(fn () => EventActivityCategoryData::collect($activity->categories)),
            teams: Lazy::create(fn () => EventActivityTeamData::collect($activity->teams)),
            registrations: Lazy::create(fn () => EventActivityRegistrationData::collect($activity->registrations)),
            media: Lazy::create(fn () => MediaData::collect(
                $activity->getMedia('gallery')->map(fn ($m) => MediaData::fromModel(
                    $m,
                    'main',
                    [
                        'main' => ['width' => 1200, 'height' => 620],
                        'hero' => ['width' => 1920, 'height' => 1080],
                    ],
                    ['hero']
                ))
            )),
        );
    }
}
