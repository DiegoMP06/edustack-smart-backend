<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\UpdateCourseContentRequest;
use App\Models\Classroom\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseContentController extends Controller
{
    public function edit(Course $course, Request $request)
    {
        $edit = $request->boolean('edit', false);

        return Inertia::render('classroom/course-content', [
            'course' => $course,
            'edit' => $edit,
            'message' => session('message'),
        ]);
    }

    public function update(UpdateCourseContentRequest $request, Course $course)
    {
        $edit = $request->boolean('edit', false);
        $data = $request->validated();

        $course->content = $data['content'];
        $course->save();

        $route = $edit ?
            back() :
            redirect()->intended(route(
                'classroom.courses.show',
                ['course' => $course],
                absolute: false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
