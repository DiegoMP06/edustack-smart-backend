<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\Classroom\CourseEnrollment;
use App\Models\User;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class EnrollUserAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, User $user): CourseEnrollment
    {
        return $this->courseWriteRepository->enrollUser($course, $user);
    }
}
