<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use App\Models\Classroom\SubmissionComment;
use App\Modules\Classroom\Application\DTOs\SubmissionCommentFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateSubmissionCommentAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteSubmissionCommentAction;
use App\Modules\Classroom\Http\Requests\StoreSubmissionCommentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubmissionCommentController extends Controller
{
    public function __construct(
        private CreateSubmissionCommentAction $createSubmissionCommentAction,
        private DeleteSubmissionCommentAction $deleteSubmissionCommentAction,
    ) {}

    private function ensureAssignmentBelongsToCourse(Course $course, Assignment $assignment): void
    {
        abort_if($assignment->course_id !== $course->id, 404);
    }

    private function ensureSubmissionBelongsToAssignment(Assignment $assignment, AssignmentSubmission $submission): void
    {
        abort_if($submission->assignment_id !== $assignment->id, 404);
    }

    private function ensureCommentBelongsToSubmission(AssignmentSubmission $submission, SubmissionComment $comment): void
    {
        abort_if($comment->assignment_submission_id !== $submission->id, 404);
    }

    public function store(StoreSubmissionCommentRequest $request, Course $course, Assignment $assignment, AssignmentSubmission $submission): RedirectResponse
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);
        $this->ensureSubmissionBelongsToAssignment($assignment, $submission);

        $data = $request->validated();
        $formData = SubmissionCommentFormData::from($data);
        $this->createSubmissionCommentAction->execute($submission, $request->user(), $formData);

        return back()->with('message', 'Comentario agregado.');
    }

    public function destroy(Request $request, Course $course, Assignment $assignment, AssignmentSubmission $submission, SubmissionComment $comment): RedirectResponse
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);
        $this->ensureSubmissionBelongsToAssignment($assignment, $submission);
        $this->ensureCommentBelongsToSubmission($submission, $comment);

        if ($comment->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $this->deleteSubmissionCommentAction->execute($comment);

        return back()->with('message', 'Comentario eliminado.');
    }
}
