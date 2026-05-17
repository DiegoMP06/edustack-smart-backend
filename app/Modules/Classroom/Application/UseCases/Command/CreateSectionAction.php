<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\Classroom\CourseSection;
use App\Modules\Classroom\Application\DTOs\CourseSectionFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CreateSectionAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, CourseSectionFormData $data): CourseSection
    {
        return $this->courseWriteRepository->createSection($course, $data);
    }
}
