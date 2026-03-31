<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user)
    {
        $user->is_active = ! $user->is_active;
        $user->save();

        return back()->with(
            'message',
            'Usuario actualizado correctamente.'
        );
    }
}
