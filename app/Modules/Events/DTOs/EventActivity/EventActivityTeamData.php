<?php

namespace App\Modules\Events\DTOs\EventActivity;

use App\Enums\Events\TeamStatus;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class EventActivityTeamData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public TeamStatus $status,
        public int $captain_user_id,
        public int $event_activity_id,
        #[TypeScriptType('Array<EventActivityTeamMemberData>')]
        #[DataCollectionOf(EventActivityTeamMemberData::class)]
        public DataCollection $members,
    ) {}
}
