<?php

use App\Http\Controllers\Classroom\CourseStatusController;
use App\Models\Classroom\Course;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('toggles the course published status and flashes a success message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/status', 'PATCH', [], [], [], ['HTTP_REFERER' => '/classroom/courses']);
    app()->instance('request', $request);

    $course = new class extends Course
    {
        public bool $saved = false;

        public function save(array $options = []): bool
        {
            $this->saved = true;

            return true;
        }
    };

    $course->is_published = false;

    $response = (new CourseStatusController)->__invoke($course);

    expect($course->is_published)->toBeTrue()
        ->and($course->saved)->toBeTrue()
        ->and($response->getTargetUrl())->toContain('/classroom/courses')
        ->and(session('message'))->toBe('Estado del curso actualizado.');
});
