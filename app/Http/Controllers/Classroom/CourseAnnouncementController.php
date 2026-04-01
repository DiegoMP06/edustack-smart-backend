<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreCourseAnnouncementRequest;
use App\Http\Requests\Classroom\UpdateCourseAnnouncementRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseAnnouncement;

class CourseAnnouncementController extends Controller
{
    private function ensureAnnouncementBelongsToCourse(Course $course, CourseAnnouncement $announcement): void
    {
        abort_if($announcement->course_id !== $course->id, 404);
    }

    public function store(StoreCourseAnnouncementRequest $request, Course $course)
    {
        $data = $request->validated();

        $course->announcements()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('message', 'Anuncio publicado.');
    }

    public function update(UpdateCourseAnnouncementRequest $request, Course $course, CourseAnnouncement $announcement)
    {
        $this->ensureAnnouncementBelongsToCourse($course, $announcement);

        $data = $request->validated();

        $announcement->update($data);

        return back()->with('message', 'Anuncio actualizado.');
    }

    public function destroy(Course $course, CourseAnnouncement $announcement)
    {
        $this->ensureAnnouncementBelongsToCourse($course, $announcement);

        $announcement->delete();

        return back()->with('message', 'Anuncio eliminado.');
    }
}
