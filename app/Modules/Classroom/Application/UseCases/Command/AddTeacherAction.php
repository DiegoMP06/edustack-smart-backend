<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\Classroom\CourseTeacher;
use App\Modules\Classroom\Application\DTOs\CourseTeacherFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class AddTeacherAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, CourseTeacherFormData $data): CourseTeacher
    {
        return $this->courseWriteRepository->addTeacher($course, $data);
    }
}
