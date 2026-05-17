<?php

namespace App\Modules\Projects\Application\DTOs;

use App\Enums\Projects\ProjectCollaboratorRole;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProjectCollaboratorFormData extends Data
{
    public function __construct(
        public int $user_id,
        public ProjectCollaboratorRole $role,
    ) {}
}
