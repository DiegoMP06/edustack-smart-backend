<?php

namespace App\Modules\Classroom\Infrastructure\Repositories;

use App\Models\Classroom\Course;
use App\Models\User;
use App\Modules\Classroom\Domain\Contracts\CourseReadRepository;
use App\Modules\Classroom\Infrastructure\Queries\Options\CourseIndexQueryOptions;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class EloquentCourseReadRepository implements CourseReadRepository
{
    public function paginateUserCourses(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        $options = new CourseIndexQueryOptions;

        return QueryBuilder::for(Course::class)
            ->where('user_id', $user->id)
            ->allowedFilters($options->filters())
            ->allowedSorts($options->sorts())
            ->allowedIncludes($options->includes())
            ->defaultSort('-id')
            ->paginate($params->per_page)
            ->withQueryString();
    }
}
