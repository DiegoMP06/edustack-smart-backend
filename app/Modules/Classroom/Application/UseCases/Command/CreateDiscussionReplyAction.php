<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseDiscussionReply;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionReplyFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CreateDiscussionReplyAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseDiscussion $discussion, User $author, CourseDiscussionReplyFormData $data): CourseDiscussionReply
    {
        return $this->courseWriteRepository->createDiscussionReply($discussion, $author, $data);
    }
}
