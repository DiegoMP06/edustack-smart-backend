<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\SubmissionStatus;
use App\Modules\Classroom\Application\DTOs\AssignmentFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateAssignmentAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteAssignmentAction;
use App\Modules\Classroom\Application\UseCases\Command\UpdateAssignmentAction;
use App\Modules\Classroom\Http\Requests\StoreAssignmentRequest;
use App\Modules\Classroom\Http\Requests\UpdateAssignmentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AssignmentController extends Controller
{
    public function __construct(
        private CreateAssignmentAction $createAssignmentAction,
        private UpdateAssignmentAction $updateAssignmentAction,
        private DeleteAssignmentAction $deleteAssignmentAction,
    ) {}

    private function formData(Course $course): array
    {
        return [
            'lessons' => $course->lessons()->where('is_published', true)
                ->orderBy('order')->get(['id', 'name', 'slug']),
            'submissionStatuses' => SubmissionStatus::orderBy('order')->get(['id', 'name', 'slug', 'color']),
        ];
    }

    private function ensureAssignmentBelongsToCourse(Course $course, Assignment $assignment): void
    {
        abort_if($assignment->course_id !== $course->id, 404);
    }

    private function validateLessonBelongsToCourse(Course $course, array $data): void
    {
        if (
            $data['course_lesson_id'] !== null
            && CourseLesson::where('id', $data['course_lesson_id'])->where('course_id', $course->id)->doesntExist()
        ) {
            throw ValidationException::withMessages([
                'course_lesson_id' => 'La lección no pertenece a este curso.',
            ]);
        }
    }

    public function create(Course $course): InertiaResponse
    {
        return Inertia::render('classroom/assignments/create-assignment', [
            ...$this->formData($course),
            'course' => $course,
        ]);
    }

    public function store(StoreAssignmentRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $this->validateLessonBelongsToCourse($course, $data);

        $formData = AssignmentFormData::from($data);
        $this->createAssignmentAction->execute($course, $request->user(), $formData);

        return back()->with('message', 'Tarea creada correctamente.');
    }

    public function edit(Course $course, Assignment $assignment, Request $request): InertiaResponse
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        return Inertia::render('classroom/assignments/edit-assignment', [
            ...$this->formData($course),
            'course' => $course,
            'assignment' => $assignment,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateAssignmentRequest $request, Course $course, Assignment $assignment): RedirectResponse
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        $data = $request->validated();
        $this->validateLessonBelongsToCourse($course, $data);

        $formData = AssignmentFormData::from($data);
        $this->updateAssignmentAction->execute($assignment, $formData);

        return back()->with('message', 'Tarea actualizada correctamente.');
    }

    public function destroy(Course $course, Assignment $assignment): RedirectResponse
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        if ($assignment->submissions()->exists()) {
            throw ValidationException::withMessages([
                'assignment' => 'No puedes eliminar una tarea con entregas registradas.',
            ]);
        }

        $this->deleteAssignmentAction->execute($assignment);

        return back()->with('message', 'Tarea eliminada correctamente.');
    }
}
