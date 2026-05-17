<?php

namespace App\Modules\Admin\DTOs;

use App\Models\User;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript()]
class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $father_last_name,
        public string $mother_last_name,
        public string $email,
        public string $created_at,
        public string $updated_at,
        public bool $is_active,
        #[DataCollectionOf(RoleData::class)]
        #[TypeScriptType('Array<RoleData>|null')]
        public Lazy|DataCollection|null $roles = null,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            father_last_name: $user->father_last_name,
            mother_last_name: $user->mother_last_name,
            email: $user->email,
            created_at: $user->created_at->toDateTimeString(),
            updated_at: $user->updated_at->toDateTimeString(),
            is_active: $user->is_active,
            roles: Lazy::create(fn () => RoleData::collect($user->roles)),
        );
    }
}
