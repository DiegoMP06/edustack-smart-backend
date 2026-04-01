<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreCourseTeacherRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseTeacher;
use Illuminate\Validation\ValidationException;

class CourseTeacherController extends Controller
{
    public function store(StoreCourseTeacherRequest $request, Course $course)
    {
        $data = $request->validated();

        if (CourseTeacher::where('course_id', $course->id)->where('user_id', $data['user_id'])->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'Este usuario ya es docente del curso.',
            ]);
        }

        $course->teachers()->create($data);

        return back()->with('message', 'Docente agregado al curso.');
    }

    public function destroy(Course $course, CourseTeacher $teacher)
    {
        if ($teacher->course_id !== $course->id) {
            abort(404);
        }

        $teacher->delete();

        return back()->with('message', 'Docente removido del curso.');
    }
}
