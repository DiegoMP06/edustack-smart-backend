<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Modules\Classroom\Application\UseCases\Command\ToggleLessonPublishedAction;
use Illuminate\Http\RedirectResponse;

class CourseLessonStatusController extends Controller
{
    public function __construct(
        private ToggleLessonPublishedAction $toggleLessonPublishedAction,
    ) {}

    public function __invoke(Course $course, CourseLesson $lesson): RedirectResponse
    {
        abort_if($lesson->course_id !== $course->id, 404);

        $this->toggleLessonPublishedAction->execute($lesson);

        return back()->with('message', 'Estado de la lección actualizado.');
    }
}
