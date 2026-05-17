<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseDiscussionReply;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class DeleteDiscussionReplyAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseDiscussionReply $reply): void
    {
        $this->courseWriteRepository->deleteDiscussionReply($reply);
    }
}
