<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use App\Modules\Classroom\Application\DTOs\AssignmentSubmissionFormData;
use App\Modules\Classroom\Application\DTOs\GradeSubmissionFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateSubmissionAction;
use App\Modules\Classroom\Application\UseCases\Command\GradeSubmissionAction;
use App\Modules\Classroom\Http\Requests\StoreAssignmentSubmissionRequest;
use App\Modules\Classroom\Http\Requests\UpdateAssignmentSubmissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class AssignmentSubmissionController extends Controller
{
    public function __construct(
        private CreateSubmissionAction $createSubmissionAction,
        private GradeSubmissionAction $gradeSubmissionAction,
    ) {}

    private function ensureAssignmentBelongsToCourse(Course $course, Assignment $assignment): void
    {
        abort_if($assignment->course_id !== $course->id, 404);
    }

    private function ensureSubmissionBelongsToAssignment(Assignment $assignment, AssignmentSubmission $submission): void
    {
        abort_if($submission->assignment_id !== $assignment->id, 404);
    }

    public function store(StoreAssignmentSubmissionRequest $request, Course $course, Assignment $assignment): RedirectResponse
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

        $formData = AssignmentSubmissionFormData::from($data);
        $this->createSubmissionAction->execute($assignment, $request->user(), $formData);

        return back()->with('message', 'Entrega realizada correctamente.');
    }

    public function update(UpdateAssignmentSubmissionRequest $request, Course $course, Assignment $assignment, AssignmentSubmission $submission): RedirectResponse
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);
        $this->ensureSubmissionBelongsToAssignment($assignment, $submission);

        if (! $request->user()->hasRole('admin') && ! $request->user()->hasRole('teacher')) {
            abort(403);
        }

        $data = $request->validated();
        $formData = GradeSubmissionFormData::from($data);
        $this->gradeSubmissionAction->execute($submission, $request->user(), $formData);

        return back()->with('message', 'Entrega calificada correctamente.');
    }
}
