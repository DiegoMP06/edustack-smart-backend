<?php

use App\Http\Controllers\Classroom\CourseContentController;
use App\Models\Classroom\Course;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('renders the course content editor with edit flag', function () {
    session()->start();

    $request = Request::create('/classroom/courses/5/content/edit?edit=1', 'GET', [], [], [], [
        'HTTP_X_INERTIA' => 'true',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
    ]);
    $request->setLaravelSession(app('session')->driver());

    $course = new Course;
    $course->id = 5;
    $course->name = 'Curso demo';

    $response = (new CourseContentController)->edit($course, $request)->toResponse($request);
    $payload = $response->getData(true);

    expect($payload['component'])->toBe('classroom/course-content')
        ->and($payload['props']['edit'])->toBeTrue()
        ->and($payload['props']['course']['id'])->toBe(5);
});

it('stores content and redirects back when edit is true', function () {
    session()->start();

    $request = Request::create('/classroom/courses/5/content?edit=1', 'PATCH', [
        'content' => [
            ['type' => 'text', 'props' => ['text' => 'Hola mundo']],
        ],
    ], [], [], ['HTTP_REFERER' => '/classroom/courses/5/content/edit']);
    $request->setLaravelSession(app('session')->driver());
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
    $course->id = 5;

    $response = (new CourseContentController)->update($request, $course);

    expect($course->saved)->toBeTrue()
        ->and($course->content)->toMatchArray([
            ['type' => 'text', 'props' => ['text' => 'Hola mundo']],
        ])
        ->and($response->getTargetUrl())->toContain('/classroom/courses/5/content/edit')
        ->and(session('message'))->toBe('Contenido guardado correctamente.');
});
