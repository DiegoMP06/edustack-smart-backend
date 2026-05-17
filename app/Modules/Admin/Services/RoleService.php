<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\DTOs\RoleData;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getAll()
    {
        $roles = Role::all();

        return RoleData::collect($roles);
    }
}
