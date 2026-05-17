<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseDiscussion;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class ToggleDiscussionClosedAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseDiscussion $discussion): CourseDiscussion
    {
        return $this->courseWriteRepository->toggleClosed($discussion);
    }
}
