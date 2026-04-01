<?php

use App\Http\Controllers\Classroom\CourseLessonContentController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('renders the lesson content editor with edit flag', function () {
    session()->start();

    $request = Request::create('/classroom/courses/8/lessons/12/content/edit?edit=1', 'GET', [], [], [], [
        'HTTP_X_INERTIA' => 'true',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
    ]);
    $request->setLaravelSession(app('session')->driver());

    $course = new Course;
    $course->id = 8;

    $lesson = new CourseLesson;
    $lesson->id = 12;
    $lesson->course_id = 8;

    $response = (new CourseLessonContentController)->edit($course, $lesson, $request)->toResponse($request);
    $payload = $response->getData(true);

    expect($payload['component'])->toBe('classroom/lesson-content')
        ->and($payload['props']['edit'])->toBeTrue()
        ->and($payload['props']['course']['id'])->toBe(8)
        ->and($payload['props']['lesson']['id'])->toBe(12);
});

it('stores lesson content and redirects back when edit is true', function () {
    session()->start();

    $request = Request::create('/classroom/courses/8/lessons/12/content?edit=1', 'PATCH', [
        'content' => [
            ['type' => 'text', 'props' => ['text' => 'Clase 1']],
        ],
    ], [], [], ['HTTP_REFERER' => '/classroom/courses/8/lessons/12/content/edit']);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 8;

    $lesson = new class extends CourseLesson
    {
        public bool $saved = false;

        public function save(array $options = []): bool
        {
            $this->saved = true;

            return true;
        }
    };
    $lesson->id = 12;
    $lesson->course_id = 8;

    $response = (new CourseLessonContentController)->update($request, $course, $lesson);

    expect($lesson->saved)->toBeTrue()
        ->and($lesson->content)->toMatchArray([
            ['type' => 'text', 'props' => ['text' => 'Clase 1']],
        ])
        ->and($response->getTargetUrl())->toContain('/classroom/courses/8/lessons/12/content/edit')
        ->and(session('message'))->toBe('Contenido guardado correctamente.');
});
