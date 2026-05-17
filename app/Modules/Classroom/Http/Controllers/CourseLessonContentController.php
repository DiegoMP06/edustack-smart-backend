<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Modules\Classroom\Application\UseCases\Command\UpdateLessonContentAction;
use App\Modules\Classroom\Http\Requests\UpdateCourseLessonContentRequest;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CourseLessonContentController extends Controller
{
    public function __construct(
        private UpdateLessonContentAction $updateLessonContentAction,
    ) {}

    private function ensureLessonBelongsToCourse(Course $course, CourseLesson $lesson): void
    {
        abort_if($lesson->course_id !== $course->id, 404);
    }

    public function edit(Course $course, CourseLesson $lesson, Request $request): InertiaResponse
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

    public function update(UpdateCourseLessonContentRequest $request, Course $course, CourseLesson $lesson): RedirectResponse
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $edit = $request->boolean('edit', false);
        $data = $request->validated();

        $formData = ModelContentFormData::from($data);
        $this->updateLessonContentAction->execute($lesson, $formData);

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
