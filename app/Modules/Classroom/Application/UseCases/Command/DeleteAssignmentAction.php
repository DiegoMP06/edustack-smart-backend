<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Assignment;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class DeleteAssignmentAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Assignment $assignment): void
    {
        $this->courseWriteRepository->deleteAssignment($assignment);
    }
}
