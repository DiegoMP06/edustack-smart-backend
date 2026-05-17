<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\SubmissionComment;
use App\Modules\Classroom\Domain\Contracts\AssignmentSubmissionWriteRepository;

class DeleteSubmissionCommentAction
{
    public function __construct(
        private AssignmentSubmissionWriteRepository $submissionWriteRepository,
    ) {}

    public function execute(SubmissionComment $comment): void
    {
        $this->submissionWriteRepository->deleteComment($comment);
    }
}
