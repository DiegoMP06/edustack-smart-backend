<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Resources\UserCollection;
use App\Modules\Admin\Services\RoleService;
use App\Modules\Admin\Services\UserService;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\Request;
use Inertia\Response;

class ListAllUsersController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService,
    ) {}

    public function __invoke(Request $request): Response
    {
        $data = ListCollectionQueryParamsData::fromRequest($request);
        $users = $this->userService->listUsers(
            $data,
            $request->user()
        );

        $roles = $this->roleService->getAll();

        return inertia('admin/users', [
            'users' => new UserCollection($users),
            'roles' => $roles,
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }
}
