<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;

class CourseLessonStatusController extends Controller
{
    public function __invoke(Course $course, CourseLesson $lesson)
    {
        abort_if($lesson->course_id !== $course->id, 404);

        $lesson->is_published = ! $lesson->is_published;
        $lesson->save();

        return back()->with('message', 'Estado de la lección actualizado.');
    }
}
