<?php

namespace App\Modules\Classroom\Infrastructure\Queries\Options;

use App\Modules\Shared\Domain\Contracts\QueryOptionsContract;
use Spatie\QueryBuilder\AllowedFilter;

class CourseIndexQueryOptions implements QueryOptionsContract
{
    public function filters(): array
    {
        return [
            AllowedFilter::exact('status', 'course_status_id'),
            AllowedFilter::exact('category', 'course_category_id'),
            'name',
        ];
    }

    public function includes(): array
    {
        return ['status', 'category', 'media'];
    }

    public function sorts(): array
    {
        return ['created_at', 'name'];
    }
}
