<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\User;
use App\Modules\Admin\Http\Requests\EditUserRoleRequest;
use App\Modules\Admin\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class EditUserRoleController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    public function __invoke(EditUserRoleRequest $request, User $user): RedirectResponse
    {
        $this->userService->editUserRole($user, $request->validated());

        return back()->with(
            'message',
            'Usuario actualizado correctamente.'
        );
    }
}
