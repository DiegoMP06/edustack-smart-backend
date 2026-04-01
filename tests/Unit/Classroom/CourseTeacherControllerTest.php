<?php

use App\Http\Controllers\Classroom\CourseTeacherController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseTeacher;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('defines expected validation rules for course teachers', function () {
    $method = new ReflectionMethod(CourseTeacherController::class, 'rules');
    $method->setAccessible(true);

    $rules = $method->invoke(new CourseTeacherController, true);

    expect($rules['user_id'])->toContain('required')
        ->and($rules['user_id'])->toContain('exists:users,id')
        ->and($rules['role'])->toContain('in:co_teacher,assistant,guest');
});

it('deletes a teacher assignment and flashes confirmation message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/3/teachers/2', 'DELETE', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/3/edit',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 3;

    $teacher = new class extends CourseTeacher
    {
        public bool $deleted = false;

        public function delete(): ?bool
        {
            $this->deleted = true;

            return true;
        }
    };
    $teacher->course_id = 3;

    $response = (new CourseTeacherController)->destroy($course, $teacher);

    expect($teacher->deleted)->toBeTrue()
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Docente removido del curso.');
});
