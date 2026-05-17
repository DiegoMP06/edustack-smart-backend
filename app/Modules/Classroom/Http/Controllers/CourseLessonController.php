<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\CourseSection;
use App\Models\Classroom\ResourceType;
use App\Modules\Classroom\Application\DTOs\CourseLessonFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateLessonAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteLessonAction;
use App\Modules\Classroom\Application\UseCases\Command\UpdateLessonAction;
use App\Modules\Classroom\Http\Requests\StoreCourseLessonRequest;
use App\Modules\Classroom\Http\Requests\UpdateCourseLessonRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CourseLessonController extends Controller
{
    public function __construct(
        private CreateLessonAction $createLessonAction,
        private UpdateLessonAction $updateLessonAction,
        private DeleteLessonAction $deleteLessonAction,
    ) {}

    private function formData(Course $course): array
    {
        return [
            'sections' => $course->sections()->orderBy('order')->get(['id', 'name']),
            'resourceTypes' => ResourceType::all(['id', 'name', 'slug', 'icon']),
        ];
    }

    private function ensureLessonBelongsToCourse(Course $course, CourseLesson $lesson): void
    {
        abort_if($lesson->course_id !== $course->id, 404);
    }

    private function validateSectionBelongsToCourse(Course $course, array $data): void
    {
        if (CourseSection::where('id', $data['course_section_id'])->where('course_id', $course->id)->doesntExist()) {
            throw ValidationException::withMessages([
                'course_section_id' => 'La sección no pertenece a este curso.',
            ]);
        }
    }

    public function create(Course $course): InertiaResponse
    {
        return Inertia::render('classroom/lessons/create-lesson', [
            ...$this->formData($course),
            'course' => $course,
        ]);
    }

    public function store(StoreCourseLessonRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $this->validateSectionBelongsToCourse($course, $data);

        $formData = CourseLessonFormData::from($data);
        $lesson = $this->createLessonAction->execute($course, $formData);

        return redirect()->intended(
            route('classroom.courses.lessons.content.edit', ['course' => $course, 'lesson' => $lesson, 'edit' => false], false)
        )->with('message', 'Lección creada correctamente.');
    }

    public function edit(Course $course, CourseLesson $lesson, Request $request): InertiaResponse
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        return Inertia::render('classroom/lessons/edit-lesson', [
            ...$this->formData($course),
            'course' => $course,
            'lesson' => $lesson,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateCourseLessonRequest $request, Course $course, CourseLesson $lesson): RedirectResponse
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $data = $request->validated();
        $this->validateSectionBelongsToCourse($course, $data);

        $formData = CourseLessonFormData::from($data);
        $this->updateLessonAction->execute($lesson, $formData);

        return back()->with('message', 'Lección actualizada correctamente.');
    }

    public function destroy(Course $course, CourseLesson $lesson): RedirectResponse
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        if ($lesson->completions()->exists()) {
            throw ValidationException::withMessages([
                'lesson' => 'No puedes eliminar una lección con progreso registrado de estudiantes.',
            ]);
        }

        $this->deleteLessonAction->execute($lesson);

        return back()->with('message', 'Lección eliminada correctamente.');
    }
}
