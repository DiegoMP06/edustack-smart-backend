<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Requests\ListAllUsersRequest;
use App\Modules\Admin\Http\Resources\UserCollection;
use App\Modules\Admin\Services\UserService;
use Illuminate\Routing\Controller;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class ListAllUsersController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function __invoke(ListAllUsersRequest $request): Response
    {
        $params = $request->validated();

        $users = $this->userService->getPaginatedUsers(
            $params,
            $request->user()
        );

        return inertia('admin/users', [
            'users' => new UserCollection($users),
            'roles' => Role::all(),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }
}
