<?php

namespace App\Modules\Classroom\Application\UseCases\Query;

use App\Modules\Classroom\Domain\Contracts\CourseFormOptionsRepository;

class GetCourseFormOptionsAction
{
    public function __construct(
        private CourseFormOptionsRepository $optionsRepository,
    ) {}

    public function execute(): array
    {
        return [
            'statuses' => $this->optionsRepository->getStatuses(),
            'categories' => $this->optionsRepository->getCategories(),
        ];
    }
}
