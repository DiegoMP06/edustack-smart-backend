<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Assignment;
use App\Models\Classroom\Course;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\AssignmentFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CreateAssignmentAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, User $author, AssignmentFormData $data): Assignment
    {
        return $this->courseWriteRepository->createAssignment($course, $author, $data);
    }
}
