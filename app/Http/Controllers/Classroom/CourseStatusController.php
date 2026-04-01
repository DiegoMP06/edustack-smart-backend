<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;

class CourseStatusController extends Controller
{
    public function __invoke(Course $course)
    {
        $course->is_published = ! $course->is_published;
        $course->save();

        return back()->with('message', 'Estado del curso actualizado.');
    }
}
