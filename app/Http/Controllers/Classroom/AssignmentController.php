<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreAssignmentRequest;
use App\Http\Requests\Classroom\UpdateAssignmentRequest;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\SubmissionStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AssignmentController extends Controller
{
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

    public function create(Course $course)
    {
        return Inertia::render('classroom/assignments/create-assignment', [
            ...$this->formData($course),
            'course' => $course,
        ]);
    }

    public function store(StoreAssignmentRequest $request, Course $course)
    {
        $data = $request->validated();

        $this->validateLessonBelongsToCourse($course, $data);

        $course->assignments()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('message', 'Tarea creada correctamente.');
    }

    public function edit(Course $course, Assignment $assignment, Request $request)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        return Inertia::render('classroom/assignments/edit-assignment', [
            ...$this->formData($course),
            'course' => $course,
            'assignment' => $assignment,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateAssignmentRequest $request, Course $course, Assignment $assignment)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        $data = $request->validated();

        $this->validateLessonBelongsToCourse($course, $data);

        $assignment->update($data);

        return back()->with('message', 'Tarea actualizada correctamente.');
    }

    public function destroy(Course $course, Assignment $assignment)
    {
        $this->ensureAssignmentBelongsToCourse($course, $assignment);

        if ($assignment->submissions()->exists()) {
            throw ValidationException::withMessages([
                'assignment' => 'No puedes eliminar una tarea con entregas registradas.',
            ]);
        }

        $assignment->delete();

        return back()->with('message', 'Tarea eliminada correctamente.');
    }
}
