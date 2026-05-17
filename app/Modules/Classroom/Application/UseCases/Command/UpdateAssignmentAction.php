<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Assignment;
use App\Modules\Classroom\Application\DTOs\AssignmentFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class UpdateAssignmentAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Assignment $assignment, AssignmentFormData $data): Assignment
    {
        return $this->courseWriteRepository->updateAssignment($assignment, $data);
    }
}
