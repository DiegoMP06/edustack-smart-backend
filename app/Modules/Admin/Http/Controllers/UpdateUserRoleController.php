<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\User;
use App\Modules\Admin\Actions\ChangeUserRoleAction;
use App\Modules\Admin\DTOs\UpdateUserRoleFormData;
use App\Modules\Admin\Http\Requests\UpdateUserRoleRequest;
use Illuminate\Http\RedirectResponse;

class UpdateUserRoleController extends Controller
{
    public function __invoke(
        UpdateUserRoleRequest $request,
        User $user,
        ChangeUserRoleAction $action
    ): RedirectResponse {
        $data = UpdateUserRoleFormData::from($request->validated());
        $action->execute($user, $data);

        return back()->with(
            'message',
            'Usuario actualizado correctamente.'
        );
    }
}
