<?php

namespace App\Modules\Blog\Domain\Contracts;

use App\Models\User;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostReadRepository
{
    public function paginateUserPosts(User $user, ListCollectionQueryParamsData $data): LengthAwarePaginator;

    public function paginatePublishedPosts(ListCollectionQueryParamsData $data): LengthAwarePaginator;
}
