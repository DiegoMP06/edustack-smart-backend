<?php

namespace App\Modules\Events\DTOs;

use App\Enums\Events\EventRegistrationStatus;
use App\Models\User;
use App\Modules\Admin\DTOs\RoleData;
use App\Modules\Admin\DTOs\UserData;
use DateTimeInterface;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class EventRegistrationData extends UserData
{
    public function __construct(
        int $id,
        string $name,
        string $father_last_name,
        string $mother_last_name,
        string $email,
        string $created_at,
        string $updated_at,
        bool $is_active,
        Lazy|DataCollection|null $roles,
        public int $pivot_id,
        public EventRegistrationStatus $pivot_status,
        #[TypeScriptType('string|null')]
        public ?DateTimeInterface $pivot_confirmed_at,
    ) {
        parent::__construct(
            $id,
            $name,
            $father_last_name,
            $mother_last_name,
            $email,
            $created_at,
            $updated_at,
            $is_active,
            $roles
        );
    }

    public static function fromModelWithPivot(User $user): self
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
            pivot_id: $user->pivot->id,
            pivot_status: EventRegistrationStatus::from($user->pivot->status),
            pivot_confirmed_at: $user->pivot->confirmed_at,
        );
    }
}
