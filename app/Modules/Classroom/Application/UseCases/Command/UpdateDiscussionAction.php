<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseDiscussion;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class UpdateDiscussionAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseDiscussion $discussion, CourseDiscussionFormData $data): CourseDiscussion
    {
        return $this->courseWriteRepository->updateDiscussion($discussion, $data);
    }
}
