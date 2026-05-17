<?php

namespace App\Modules\Admin\Services;

use App\Models\User;
use App\Modules\Admin\DTOs\UserData;
use App\Modules\Admin\Queries\ListAllUsersQuery;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected ListAllUsersQuery $listAllUsersQuery,
    ) {}

    public function listUsers(ListCollectionQueryParamsData $data, User $user): LengthAwarePaginator
    {
        $users = $this->listAllUsersQuery->paginate(
            params: ['except_user_id' => $user->id],
            perPage: $data->per_page,
            defaultIncludes: ['roles']
        );

        $users->getCollection()->transform(function (User $user) {
            return UserData::fromModel($user)
                ->include('roles');
        });

        return $users;
    }
}
