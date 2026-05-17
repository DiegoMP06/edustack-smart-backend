<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseLesson;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class ToggleLessonPublishedAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseLesson $lesson): CourseLesson
    {
        return $this->courseWriteRepository->toggleLessonPublished($lesson);
    }
}
