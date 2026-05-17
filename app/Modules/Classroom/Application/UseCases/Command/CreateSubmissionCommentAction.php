<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\SubmissionComment;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\SubmissionCommentFormData;
use App\Modules\Classroom\Domain\Contracts\AssignmentSubmissionWriteRepository;

class CreateSubmissionCommentAction
{
    public function __construct(
        private AssignmentSubmissionWriteRepository $submissionWriteRepository,
    ) {}

    public function execute(AssignmentSubmission $submission, User $author, SubmissionCommentFormData $data): SubmissionComment
    {
        return $this->submissionWriteRepository->createComment($submission, $author, $data);
    }
}
