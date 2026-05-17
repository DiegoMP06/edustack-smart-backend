<?php

namespace App\Modules\Shared\Services;

use App\Models\User;
use App\Modules\Admin\DTOs\UserData;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use App\Modules\Shared\Queries\ListActiveUsersQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersService
{
    public function __construct(
        protected ListActiveUsersQuery $listActiveUsersQuery,
    ) {}

    public function listActiveUsers(ListCollectionQueryParamsData $data, User $user): LengthAwarePaginator
    {
        $users = $this->listActiveUsersQuery->paginate(
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
