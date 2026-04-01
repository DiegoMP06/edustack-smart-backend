<?php

use App\Http\Controllers\Classroom\CourseDiscussionController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('defines expected validation rules for discussions', function () {
    $method = new ReflectionMethod(CourseDiscussionController::class, 'rules');
    $method->setAccessible(true);

    $rules = $method->invoke(new CourseDiscussionController, true);

    expect($rules['title'])->toContain('required')
        ->and($rules['content'])->toContain('array')
        ->and($rules['is_pinned'])->toContain('boolean')
        ->and($rules['course_lesson_id'])->toContain('exists:course_lessons,id');
});

it('deletes a discussion and flashes confirmation message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/8/discussions/3', 'DELETE', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/8/edit',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 8;

    $discussion = new class extends CourseDiscussion
    {
        public bool $deleted = false;

        public function delete(): ?bool
        {
            $this->deleted = true;

            return true;
        }
    };
    $discussion->course_id = 8;

    $response = (new CourseDiscussionController)->destroy($course, $discussion);

    expect($discussion->deleted)->toBeTrue()
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Discusion eliminada.');
});
