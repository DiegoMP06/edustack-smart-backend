<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\User;
use App\Modules\Admin\Actions\EditUserStatusAction;
use Illuminate\Http\RedirectResponse;

class UpdateUserStatusController extends Controller
{
    public function __invoke(
        User $user,
        EditUserStatusAction $action
    ): RedirectResponse {
        $action->execute($user);

        return back()->with(
            'message',
            'Usuario actualizado correctamente.'
        );
    }
}
