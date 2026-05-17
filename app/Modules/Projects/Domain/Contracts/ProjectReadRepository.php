<?php

namespace App\Modules\Projects\Domain\Contracts;

use App\Models\User;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectReadRepository
{
    public function paginateUserProjects(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator;

    public function paginatePublishedProjects(ListCollectionQueryParamsData $params): LengthAwarePaginator;
}
