<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseLesson;
use App\Models\User;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class UncompleteLessonAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(User $user, CourseLesson $lesson): void
    {
        $this->courseWriteRepository->uncompleteLesson($user, $lesson);
    }
}
