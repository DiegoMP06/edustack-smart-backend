<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\AssignmentSubmissionFormData;
use App\Modules\Classroom\Domain\Contracts\AssignmentSubmissionWriteRepository;

class CreateSubmissionAction
{
    public function __construct(
        private AssignmentSubmissionWriteRepository $submissionWriteRepository,
    ) {}

    public function execute(Assignment $assignment, User $user, AssignmentSubmissionFormData $data): AssignmentSubmission
    {
        return $this->submissionWriteRepository->createSubmission($assignment, $user, $data);
    }
}
