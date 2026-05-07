<?php

namespace App\Modules\Admin\Services;

use App\Models\User;
use App\Modules\Admin\Actions\EditUserStatusAction;
use App\Modules\Admin\Actions\SyncUserRoleAction;
use App\Modules\Admin\DTOs\UserData;
use App\Modules\Admin\Queries\ListAllUsersQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected ListAllUsersQuery $listAllUsersQuery,
        protected SyncUserRoleAction $syncUserRoleAction,
        protected EditUserStatusAction $editUserStatusAction,
    ) {
    }

    public function getPaginatedUsers(array $data, User $user): LengthAwarePaginator
    {
        $users = $this->listAllUsersQuery->paginate(
            params: ['except_user_id' => $user->id],
            perPage: $data['per_page'] ?? 20,
            defaultIncludes: ['roles']
        );

        $users->getCollection()->transform(function (User $user) {
            return UserData::fromModel($user);
        });

        return $users;
    }

    public function editUserRole(User $user, array $data): void
    {
        $this->syncUserRoleAction->execute($user, $data['role']);
    }

    public function editUserStatus(User $user): void
    {
        $this->editUserStatusAction->execute($user);
    }
}
