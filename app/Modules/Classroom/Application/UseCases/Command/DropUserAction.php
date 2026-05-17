<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\User;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class DropUserAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, User $user): void
    {
        $this->courseWriteRepository->dropUser($course, $user);
    }
}
