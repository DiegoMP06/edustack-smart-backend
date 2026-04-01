<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreCourseSectionRequest;
use App\Http\Requests\Classroom\UpdateCourseSectionRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseSection;
use Illuminate\Validation\ValidationException;

class CourseSectionController extends Controller
{
    private function ensureSectionBelongsToCourse(Course $course, CourseSection $section): void
    {
        abort_if($section->course_id !== $course->id, 404);
    }

    public function store(StoreCourseSectionRequest $request, Course $course)
    {
        $data = $request->validated();

        if ($course->sections()->where('order', $data['order'])->exists()) {
            throw ValidationException::withMessages([
                'order' => 'Ya existe una sección con ese orden en este curso.',
            ]);
        }

        $course->sections()->create($data);

        return back()->with('message', 'Sección creada correctamente.');
    }

    public function update(UpdateCourseSectionRequest $request, Course $course, CourseSection $section)
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        $data = $request->validated();

        if ($course->sections()->where('order', $data['order'])->where('id', '!=', $section->id)->exists()) {
            throw ValidationException::withMessages([
                'order' => 'Ya existe una sección con ese orden en este curso.',
            ]);
        }

        $section->update($data);

        return back()->with('message', 'Sección actualizada correctamente.');
    }

    public function destroy(Course $course, CourseSection $section)
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        if ($section->lessons()->where('is_published', true)->exists()) {
            throw ValidationException::withMessages([
                'section' => 'No puedes eliminar una sección con lecciones publicadas.',
            ]);
        }

        $section->delete();

        return back()->with('message', 'Sección eliminada correctamente.');
    }
}
