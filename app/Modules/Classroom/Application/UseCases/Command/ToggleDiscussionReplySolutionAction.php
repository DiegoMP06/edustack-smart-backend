<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseDiscussionReply;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class ToggleDiscussionReplySolutionAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseDiscussionReply $reply): CourseDiscussionReply
    {
        return $this->courseWriteRepository->toggleSolution($reply);
    }
}
