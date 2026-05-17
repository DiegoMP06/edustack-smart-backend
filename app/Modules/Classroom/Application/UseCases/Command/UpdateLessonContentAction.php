<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseLesson;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class UpdateLessonContentAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseLesson $lesson, ModelContentFormData $data): CourseLesson
    {
        return $this->courseWriteRepository->updateLessonContent($lesson, $data);
    }
}
