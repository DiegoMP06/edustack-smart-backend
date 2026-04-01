<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreCourseLessonRequest;
use App\Http\Requests\Classroom\UpdateCourseLessonRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\CourseSection;
use App\Models\Classroom\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CourseLessonController extends Controller
{
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

    public function create(Course $course)
    {
        return Inertia::render('classroom/lessons/create-lesson', [
            ...$this->formData($course),
            'course' => $course,
        ]);
    }

    public function store(StoreCourseLessonRequest $request, Course $course)
    {
        $data = $request->validated();

        $this->validateSectionBelongsToCourse($course, $data);

        $lesson = $course->lessons()->create($data);

        return redirect()->intended(
            route('classroom.courses.lessons.content.edit', ['course' => $course, 'lesson' => $lesson, 'edit' => false], false)
        )->with('message', 'Lección creada correctamente.');
    }

    public function edit(Course $course, CourseLesson $lesson, Request $request)
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        return Inertia::render('classroom/lessons/edit-lesson', [
            ...$this->formData($course),
            'course' => $course,
            'lesson' => $lesson,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateCourseLessonRequest $request, Course $course, CourseLesson $lesson)
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $data = $request->validated();

        $this->validateSectionBelongsToCourse($course, $data);

        $lesson->update($data);

        return back()->with('message', 'Lección actualizada correctamente.');
    }

    public function destroy(Course $course, CourseLesson $lesson)
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        if ($lesson->completions()->exists()) {
            throw ValidationException::withMessages([
                'lesson' => 'No puedes eliminar una lección con progreso registrado de estudiantes.',
            ]);
        }

        $lesson->delete();

        return back()->with('message', 'Lección eliminada correctamente.');
    }
}
