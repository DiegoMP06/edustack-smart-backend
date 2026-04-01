<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;

class CourseDiscussionCloseController extends Controller
{
    public function __invoke(Course $course, CourseDiscussion $discussion)
    {
        if ($discussion->course_id !== $course->id) {
            abort(404);
        }

        $discussion->is_closed = ! $discussion->is_closed;
        $discussion->save();

        return back()->with('message', 'Estado de la discusion actualizado.');
    }
}
