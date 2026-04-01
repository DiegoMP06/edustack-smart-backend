<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreSubmissionCommentRequest;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use App\Models\Classroom\SubmissionComment;
use Illuminate\Http\Request;

class SubmissionCommentController extends Controller
{
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

    public function store(StoreSubmissionCommentRequest $request, Course $course, Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);
        $this->ensureSubmissionBelongsToAssignment($assignment, $submission);

        $data = $request->validated();

        $submission->comments()->create([
            'content' => $data['content'],
            'user_id' => $request->user()->id,
        ]);

        return back()->with('message', 'Comentario agregado.');
    }

    public function destroy(Request $request, Course $course, Assignment $assignment, AssignmentSubmission $submission, SubmissionComment $comment)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);
        $this->ensureSubmissionBelongsToAssignment($assignment, $submission);
        $this->ensureCommentBelongsToSubmission($submission, $comment);

        if ($comment->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $comment->delete();

        return back()->with('message', 'Comentario eliminado.');
    }
}
