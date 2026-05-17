<?php

namespace App\Modules\Classroom\Infrastructure\Repositories;

use App\Models\Classroom\CourseCategory;
use App\Models\Classroom\CourseStatus;
use App\Modules\Classroom\Domain\Contracts\CourseFormOptionsRepository;

class EloquentCourseFormOptionsRepository implements CourseFormOptionsRepository
{
    public function getCategories(): array
    {
        return CourseCategory::orderBy('order')
            ->get(['id', 'name', 'slug', 'color', 'icon'])
            ->toArray();
    }

    public function getStatuses(): array
    {
        return CourseStatus::orderBy('order')
            ->get(['id', 'name', 'slug', 'color'])
            ->toArray();
    }
}
