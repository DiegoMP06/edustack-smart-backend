<?php

use App\Http\Controllers\Classroom\CourseDiscussionCloseController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('toggles discussion closed status and flashes message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/5/discussions/2/close', 'PATCH', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/5/edit',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 5;

    $discussion = new class extends CourseDiscussion
    {
        public bool $saved = false;

        public function save(array $options = []): bool
        {
            $this->saved = true;

            return true;
        }
    };
    $discussion->course_id = 5;
    $discussion->is_closed = false;

    $response = (new CourseDiscussionCloseController)->__invoke($course, $discussion);

    expect($discussion->is_closed)->toBeTrue()
        ->and($discussion->saved)->toBeTrue()
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Estado de la discusion actualizado.');
});
