<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class DeleteCourseAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course): void
    {
        $this->courseWriteRepository->delete($course);
    }
}
