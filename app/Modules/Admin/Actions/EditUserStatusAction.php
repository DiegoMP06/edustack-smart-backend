<?php

namespace App\Modules\Admin\Actions;

use App\Models\User;

class EditUserStatusAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user): User
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return $user;
    }
}
