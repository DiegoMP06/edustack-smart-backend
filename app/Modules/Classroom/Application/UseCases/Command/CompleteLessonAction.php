<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\LessonCompletion;
use App\Models\User;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CompleteLessonAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(User $user, CourseLesson $lesson): LessonCompletion
    {
        return $this->courseWriteRepository->completeLesson($user, $lesson);
    }
}
