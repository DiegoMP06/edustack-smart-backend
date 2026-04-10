<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Concerns\ApiQueryable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class AdminUsersController extends Controller
{
    use ApiQueryable;

    public function __invoke(Request $request)
    {
        $users = $this->buildQuery(
            User::whereNot('id', '=', $request->user()->id),
            defaultIncludes: ['roles']
        )->paginate(20)->withQueryString();

        $roles = Role::all();

        return Inertia::render('admin/users', [
            'users' => new UserCollection($users),
            'roles' => $roles,
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }
}
