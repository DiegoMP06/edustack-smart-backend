<?php

namespace App\Modules\Events\DTOs;

use App\Enums\Events\EventCollaboratorRole;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EventCollaboratorFormData extends Data
{
    public function __construct(
        public int $user_id,
        public EventCollaboratorRole $role,
    ) {}
}
