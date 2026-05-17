<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseSection;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class DeleteSectionAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseSection $section): void
    {
        $this->courseWriteRepository->deleteSection($section);
    }
}
