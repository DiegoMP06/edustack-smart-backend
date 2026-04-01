<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreAssignmentSubmissionRequest;
use App\Http\Requests\Classroom\UpdateAssignmentSubmissionRequest;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use App\Models\Classroom\SubmissionStatus;
use Illuminate\Validation\ValidationException;

class AssignmentSubmissionController extends Controller
{
    private function ensureAssignmentBelongsToCourse(Course $course, Assignment $assignment): void
    {
        abort_if($assignment->course_id !== $course->id, 404);
    }

    private function ensureSubmissionBelongsToAssignment(Assignment $assignment, AssignmentSubmission $submission): void
    {
        abort_if($submission->assignment_id !== $assignment->id, 404);
    }

    public function store(StoreAssignmentSubmissionRequest $request, Course $course, Assignment $assignment)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        $data = $request->validated();

        if (! $course->enrollments()->where('user_id', $request->user()->id)->where('status', 'active')->exists()) {
            throw ValidationException::withMessages([
                'enrollment' => 'Debes estar inscrito al curso para marcar lecciones.',
            ]);
        }

        if ($assignment->available_from && now()->lt($assignment->available_from)) {
            throw ValidationException::withMessages([
                'assignment' => 'La tarea aún no está disponible.',
            ]);
        }

        $attempts = $assignment->submissions()
            ->where('user_id', $request->user()->id)
            ->count();

        if ($attempts >= $assignment->max_attempts) {
            throw ValidationException::withMessages([
                'assignment' => 'Has alcanzado el máximo de intentos permitidos.',
            ]);
        }

        $isLate = $assignment->due_date && now()->isAfter($assignment->due_date);

        if ($isLate && ! $assignment->allow_late_submissions) {
            throw ValidationException::withMessages([
                'assignment' => 'La fecha de entrega ha vencido.',
            ]);
        }

        $statusId = SubmissionStatus::where('slug', 'submitted')->value('id');

        $assignment->submissions()->create([
            'user_id' => $request->user()->id,
            'text_content' => $data['text_content'] ?? null,
            'url_content' => $data['url_content'] ?? null,
            'attempt_number' => $attempts + 1,
            'submission_status_id' => $statusId,
            'is_late' => $isLate,
            'submitted_at' => now(),
        ]);

        return back()->with('message', 'Entrega realizada correctamente.');
    }

    public function update(UpdateAssignmentSubmissionRequest $request, Course $course, Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);
        $this->ensureSubmissionBelongsToAssignment($assignment, $submission);

        if (! $request->user()->hasRole('admin') && ! $request->user()->hasRole('teacher')) {
            abort(403);
        }

        $data = $request->validated();

        $gradedStatusId = SubmissionStatus::where('slug', 'graded')->value('id');

        $submission->update([
            'score' => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'graded_by' => $request->user()->id,
            'graded_at' => now(),
            'submission_status_id' => $gradedStatusId,
        ]);

        return back()->with('message', 'Entrega calificada correctamente.');
    }
}
