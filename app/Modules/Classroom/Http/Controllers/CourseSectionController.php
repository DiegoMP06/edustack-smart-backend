<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseSection;
use App\Modules\Classroom\Application\DTOs\CourseSectionFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateSectionAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteSectionAction;
use App\Modules\Classroom\Application\UseCases\Command\UpdateSectionAction;
use App\Modules\Classroom\Http\Requests\StoreCourseSectionRequest;
use App\Modules\Classroom\Http\Requests\UpdateCourseSectionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CourseSectionController extends Controller
{
    public function __construct(
        private CreateSectionAction $createSectionAction,
        private UpdateSectionAction $updateSectionAction,
        private DeleteSectionAction $deleteSectionAction,
    ) {}

    private function ensureSectionBelongsToCourse(Course $course, CourseSection $section): void
    {
        abort_if($section->course_id !== $course->id, 404);
    }

    public function store(StoreCourseSectionRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();

        if ($course->sections()->where('order', $data['order'])->exists()) {
            throw ValidationException::withMessages([
                'order' => 'Ya existe una sección con ese orden en este curso.',
            ]);
        }

        $formData = CourseSectionFormData::from($data);
        $this->createSectionAction->execute($course, $formData);

        return back()->with('message', 'Sección creada correctamente.');
    }

    public function update(UpdateCourseSectionRequest $request, Course $course, CourseSection $section): RedirectResponse
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        $data = $request->validated();

        if ($course->sections()->where('order', $data['order'])->where('id', '!=', $section->id)->exists()) {
            throw ValidationException::withMessages([
                'order' => 'Ya existe una sección con ese orden en este curso.',
            ]);
        }

        $formData = CourseSectionFormData::from($data);
        $this->updateSectionAction->execute($section, $formData);

        return back()->with('message', 'Sección actualizada correctamente.');
    }

    public function destroy(Course $course, CourseSection $section): RedirectResponse
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        if ($section->lessons()->where('is_published', true)->exists()) {
            throw ValidationException::withMessages([
                'section' => 'No puedes eliminar una sección con lecciones publicadas.',
            ]);
        }

        $this->deleteSectionAction->execute($section);

        return back()->with('message', 'Sección eliminada correctamente.');
    }
}
