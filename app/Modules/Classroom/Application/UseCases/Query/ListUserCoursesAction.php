<?php

namespace App\Modules\Classroom\Application\UseCases\Query;

use App\Models\User;
use App\Modules\Classroom\Domain\Contracts\CourseReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUserCoursesAction
{
    public function __construct(
        private CourseReadRepository $courseReadRepository,
    ) {}

    public function execute(ListCollectionQueryParamsData $data, User $user): LengthAwarePaginator
    {
        return $this->courseReadRepository->paginateUserCourses($user, $data);
    }
}
