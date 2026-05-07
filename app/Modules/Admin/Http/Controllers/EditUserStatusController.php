<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\User;
use App\Modules\Admin\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class EditUserStatusController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    public function __invoke(User $user): RedirectResponse
    {
        $this->userService->editUserStatus($user);

        return back()->with(
            'message',
            'Usuario actualizado correctamente.'
        );
    }
}
