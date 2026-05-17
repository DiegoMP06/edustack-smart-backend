<?php

namespace App\Modules\Classroom\Domain\Contracts;

interface CourseFormOptionsRepository
{
    public function getCategories(): array;

    public function getStatuses(): array;
}
