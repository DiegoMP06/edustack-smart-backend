<?php

use App\Http\Controllers\Classroom\CourseLessonStatusController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('toggles lesson published status and flashes a success message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/lessons/2/status', 'PATCH', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/1/edit',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 1;

    $lesson = new class extends CourseLesson
    {
        public bool $saved = false;

        public function save(array $options = []): bool
        {
            $this->saved = true;

            return true;
        }
    };
    $lesson->course_id = 1;
    $lesson->is_published = false;

    $response = (new CourseLessonStatusController)->__invoke($course, $lesson);

    expect($lesson->is_published)->toBeTrue()
        ->and($lesson->saved)->toBeTrue()
        ->and($response->getTargetUrl())->toContain('/classroom/courses/1/edit')
        ->and(session('message'))->toBe('Estado de la lección actualizado.');
});
