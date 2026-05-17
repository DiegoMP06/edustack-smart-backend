<?php

namespace App\Modules\Admin\Actions;

use App\Models\User;
use App\Modules\Admin\DTOs\UpdateUserRoleFormData;

class ChangeUserRoleAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, UpdateUserRoleFormData $data): User
    {
        $user->syncRoles($data->role);

        return $user;
    }
}
