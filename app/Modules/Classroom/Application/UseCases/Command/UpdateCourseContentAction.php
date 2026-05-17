<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class UpdateCourseContentAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, ModelContentFormData $data): Course
    {
        return $this->courseWriteRepository->updateContent($course, $data);
    }
}
