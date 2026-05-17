<?php

namespace App\Modules\Classroom\Domain\Contracts;

use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\SubmissionComment;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\AssignmentSubmissionFormData;
use App\Modules\Classroom\Application\DTOs\GradeSubmissionFormData;
use App\Modules\Classroom\Application\DTOs\SubmissionCommentFormData;

interface AssignmentSubmissionWriteRepository
{
    public function createSubmission(Assignment $assignment, User $user, AssignmentSubmissionFormData $data): AssignmentSubmission;

    public function gradeSubmission(AssignmentSubmission $submission, User $gradedBy, GradeSubmissionFormData $data): AssignmentSubmission;

    public function deleteSubmission(AssignmentSubmission $submission): void;

    public function createComment(AssignmentSubmission $submission, User $author, SubmissionCommentFormData $data): SubmissionComment;

    public function deleteComment(SubmissionComment $comment): void;
}
