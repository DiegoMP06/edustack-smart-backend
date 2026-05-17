<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\AssignmentSubmission;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\GradeSubmissionFormData;
use App\Modules\Classroom\Domain\Contracts\AssignmentSubmissionWriteRepository;

class GradeSubmissionAction
{
    public function __construct(
        private AssignmentSubmissionWriteRepository $submissionWriteRepository,
    ) {}

    public function execute(AssignmentSubmission $submission, User $gradedBy, GradeSubmissionFormData $data): AssignmentSubmission
    {
        return $this->submissionWriteRepository->gradeSubmission($submission, $gradedBy, $data);
    }
}
