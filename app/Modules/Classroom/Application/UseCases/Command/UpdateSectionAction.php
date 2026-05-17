<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseSection;
use App\Modules\Classroom\Application\DTOs\CourseSectionFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class UpdateSectionAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseSection $section, CourseSectionFormData $data): CourseSection
    {
        return $this->courseWriteRepository->updateSection($section, $data);
    }
}
