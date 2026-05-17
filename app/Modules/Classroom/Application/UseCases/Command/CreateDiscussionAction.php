<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CreateDiscussionAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, User $author, CourseDiscussionFormData $data): CourseDiscussion
    {
        return $this->courseWriteRepository->createDiscussion($course, $author, $data);
    }
}
