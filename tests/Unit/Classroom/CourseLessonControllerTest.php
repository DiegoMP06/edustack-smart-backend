<?php

use App\Http\Controllers\Classroom\CourseLessonController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

it('defines expected validation rules for lessons', function () {
    $method = new ReflectionMethod(CourseLessonController::class, 'rules');
    $method->setAccessible(true);

    $rules = $method->invoke(new CourseLessonController, true);

    expect($rules['type'])->toContain('in:text,video,activity,live')
        ->and($rules['video_url'])->toContain('required_if:type,video')
        ->and($rules['video_duration_seconds'])->toContain('required_if:type,video')
        ->and($rules['estimated_minutes'])->toContain('max:600')
        ->and($rules['course_section_id'])->toContain('exists:course_sections,id');
});

it('throws validation exception when deleting a lesson with completions', function () {
    $course = new Course;
    $course->id = 10;

    $completionsRelation = new class
    {
        public function exists(): bool
        {
            return true;
        }
    };

    $lesson = new class($completionsRelation) extends CourseLesson
    {
        public function __construct(private object $completionsRelation) {}

        public function completions(): object
        {
            return $this->completionsRelation;
        }
    };
    $lesson->course_id = 10;

    try {
        (new CourseLessonController)->destroy($course, $lesson);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('lesson')
            ->and($exception->errors()['lesson'][0])
            ->toBe('No puedes eliminar una lección con progreso registrado de estudiantes.');
    }
});

it('deletes lesson when it has no completions and flashes message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/10/edit', 'DELETE', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/10/edit',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 10;

    $completionsRelation = new class
    {
        public function exists(): bool
        {
            return false;
        }
    };

    $lesson = new class($completionsRelation) extends CourseLesson
    {
        public bool $deleted = false;

        public function __construct(private object $completionsRelation) {}

        public function completions(): object
        {
            return $this->completionsRelation;
        }

        public function delete(): ?bool
        {
            $this->deleted = true;

            return true;
        }
    };
    $lesson->course_id = 10;

    $response = (new CourseLessonController)->destroy($course, $lesson);

    expect($lesson->deleted)->toBeTrue()
        ->and($response->getTargetUrl())->toContain('/classroom/courses/10/edit')
        ->and(session('message'))->toBe('Lección eliminada correctamente.');
});
