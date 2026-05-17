<?php

namespace App\Modules\Classroom\Domain\Contracts;

use App\Models\User;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

interface CourseReadRepository
{
    public function paginateUserCourses(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator;
}
