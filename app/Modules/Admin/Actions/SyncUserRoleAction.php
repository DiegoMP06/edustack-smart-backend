<?php

namespace App\Modules\Admin\Actions;

use App\Models\User;

class SyncUserRoleAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, string $role): User
    {
        $user->syncRoles($role);

        return $user;
    }
}
