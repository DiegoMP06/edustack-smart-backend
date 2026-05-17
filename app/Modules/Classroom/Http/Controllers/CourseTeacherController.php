<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseTeacher;
use App\Modules\Classroom\Application\DTOs\CourseTeacherFormData;
use App\Modules\Classroom\Application\UseCases\Command\AddTeacherAction;
use App\Modules\Classroom\Application\UseCases\Command\RemoveTeacherAction;
use App\Modules\Classroom\Http\Requests\StoreCourseTeacherRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CourseTeacherController extends Controller
{
    public function __construct(
        private AddTeacherAction $addTeacherAction,
        private RemoveTeacherAction $removeTeacherAction,
    ) {}

    public function store(StoreCourseTeacherRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();

        if (CourseTeacher::where('course_id', $course->id)->where('user_id', $data['user_id'])->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'Este usuario ya es docente del curso.',
            ]);
        }

        $formData = CourseTeacherFormData::from($data);
        $this->addTeacherAction->execute($course, $formData);

        return back()->with('message', 'Docente agregado al curso.');
    }

    public function destroy(Course $course, CourseTeacher $teacher): RedirectResponse
    {
        if ($teacher->course_id !== $course->id) {
            abort(404);
        }

        $this->removeTeacherAction->execute($course, $teacher->user_id);

        return back()->with('message', 'Docente removido del curso.');
    }
}
