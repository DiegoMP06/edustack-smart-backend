<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseAnnouncement;
use App\Modules\Classroom\Application\DTOs\CourseAnnouncementFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateAnnouncementAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteAnnouncementAction;
use App\Modules\Classroom\Application\UseCases\Command\UpdateAnnouncementAction;
use App\Modules\Classroom\Http\Requests\StoreCourseAnnouncementRequest;
use App\Modules\Classroom\Http\Requests\UpdateCourseAnnouncementRequest;
use Illuminate\Http\RedirectResponse;

class CourseAnnouncementController extends Controller
{
    public function __construct(
        private CreateAnnouncementAction $createAnnouncementAction,
        private UpdateAnnouncementAction $updateAnnouncementAction,
        private DeleteAnnouncementAction $deleteAnnouncementAction,
    ) {}

    private function ensureAnnouncementBelongsToCourse(Course $course, CourseAnnouncement $announcement): void
    {
        abort_if($announcement->course_id !== $course->id, 404);
    }

    public function store(StoreCourseAnnouncementRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $formData = CourseAnnouncementFormData::from($data);
        $this->createAnnouncementAction->execute($course, $request->user(), $formData);

        return back()->with('message', 'Anuncio publicado.');
    }

    public function update(UpdateCourseAnnouncementRequest $request, Course $course, CourseAnnouncement $announcement): RedirectResponse
    {
        $this->ensureAnnouncementBelongsToCourse($course, $announcement);

        $data = $request->validated();
        $formData = CourseAnnouncementFormData::from($data);
        $this->updateAnnouncementAction->execute($announcement, $formData);

        return back()->with('message', 'Anuncio actualizado.');
    }

    public function destroy(Course $course, CourseAnnouncement $announcement): RedirectResponse
    {
        $this->ensureAnnouncementBelongsToCourse($course, $announcement);

        $this->deleteAnnouncementAction->execute($announcement);

        return back()->with('message', 'Anuncio eliminado.');
    }
}
