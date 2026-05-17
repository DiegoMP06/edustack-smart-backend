<?php

namespace App\Modules\Classroom\Infrastructure\Repositories;

use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\SubmissionComment;
use App\Models\Classroom\SubmissionStatus;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\AssignmentSubmissionFormData;
use App\Modules\Classroom\Application\DTOs\GradeSubmissionFormData;
use App\Modules\Classroom\Application\DTOs\SubmissionCommentFormData;
use App\Modules\Classroom\Domain\Contracts\AssignmentSubmissionWriteRepository;

class EloquentAssignmentSubmissionWriteRepository implements AssignmentSubmissionWriteRepository
{
    public function createSubmission(Assignment $assignment, User $user, AssignmentSubmissionFormData $data): AssignmentSubmission
    {
        $attempts = $assignment->submissions()
            ->where('user_id', $user->id)
            ->count();

        $isLate = $assignment->due_date && now()->isAfter($assignment->due_date);
        $statusId = SubmissionStatus::where('slug', 'submitted')->value('id');

        return $assignment->submissions()->create([
            'user_id' => $user->id,
            'text_content' => $data->text_content,
            'url_content' => $data->url_content,
            'attempt_number' => $attempts + 1,
            'submission_status_id' => $statusId,
            'is_late' => $isLate,
            'submitted_at' => now(),
        ]);
    }

    public function gradeSubmission(AssignmentSubmission $submission, User $gradedBy, GradeSubmissionFormData $data): AssignmentSubmission
    {
        $gradedStatusId = SubmissionStatus::where('slug', 'graded')->value('id');

        $submission->update([
            'score' => $data->score,
            'feedback' => $data->feedback,
            'graded_by' => $gradedBy->id,
            'graded_at' => now(),
            'submission_status_id' => $gradedStatusId,
        ]);

        return $submission;
    }

    public function deleteSubmission(AssignmentSubmission $submission): void
    {
        $submission->deleteOrFail();
    }

    public function createComment(AssignmentSubmission $submission, User $author, SubmissionCommentFormData $data): SubmissionComment
    {
        return $submission->comments()->create([
            'content' => $data->content,
            'user_id' => $author->id,
        ]);
    }

    public function deleteComment(SubmissionComment $comment): void
    {
        $comment->deleteOrFail();
    }
}
