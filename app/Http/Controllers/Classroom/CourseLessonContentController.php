<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\UpdateCourseLessonContentRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseLessonContentController extends Controller
{
    private function ensureLessonBelongsToCourse(Course $course, CourseLesson $lesson): void
    {
        abort_if($lesson->course_id !== $course->id, 404);
    }

    public function edit(Course $course, CourseLesson $lesson, Request $request)
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $edit = $request->boolean('edit', false);

        return Inertia::render('classroom/lesson-content', [
            'course' => $course,
            'lesson' => $lesson,
            'edit' => $edit,
            'message' => session('message'),
        ]);
    }

    public function update(UpdateCourseLessonContentRequest $request, Course $course, CourseLesson $lesson)
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $edit = $request->boolean('edit', false);
        $data = $request->validated();

        $lesson->content = $data['content'];
        $lesson->save();

        $route = $edit ?
            back() :
            redirect()->intended(route(
                'classroom.courses.lessons.show',
                ['course' => $course, 'lesson' => $lesson],
                absolute: false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
