<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Modules\Classroom\Application\DTOs\CourseLessonFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CreateLessonAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, CourseLessonFormData $data): CourseLesson
    {
        return $this->courseWriteRepository->createLesson($course, $data);
    }
}
