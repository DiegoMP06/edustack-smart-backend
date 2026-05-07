<?php

namespace App\Modules\Admin\DTOs;

use App\Models\User;

readonly class UserData
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
        public array $roles,
    ) {
    }

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
            roles: $user->roles->pluck('name')->toArray(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'father_last_name' => $this->father_last_name,
            'mother_last_name' => $this->mother_last_name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
            'roles' => $this->roles,
        ];
    }
}
