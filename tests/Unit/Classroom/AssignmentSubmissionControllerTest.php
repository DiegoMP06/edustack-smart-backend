<?php

use App\Http\Controllers\Classroom\AssignmentSubmissionController;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

uses(TestCase::class);

it('builds dynamic store rules by submission type', function () {
    $method = new ReflectionMethod(AssignmentSubmissionController::class, 'storeRules');
    $method->setAccessible(true);

    $controller = new AssignmentSubmissionController;

    $textAssignment = new Assignment;
    $textAssignment->submission_type = 'text';

    $urlAssignment = new Assignment;
    $urlAssignment->submission_type = 'url';

    $mixedAssignment = new Assignment;
    $mixedAssignment->submission_type = 'mixed';

    expect($method->invoke($controller, $textAssignment))
        ->toHaveKey('text_content')
        ->and($method->invoke($controller, $urlAssignment))
        ->toHaveKey('url_content')
        ->and($method->invoke($controller, $mixedAssignment))
        ->toHaveKeys(['text_content', 'url_content']);
});

it('throws validation exception when user is not actively enrolled', function () {
    $request = Request::create('/classroom/courses/5/assignments/9/submissions', 'POST');
    $request->setUserResolver(fn () => new class
    {
        public int $id = 99;
    });

    $enrollments = new class
    {
        public function where(string $column, mixed $value): self
        {
            return $this;
        }

        public function exists(): bool
        {
            return false;
        }
    };

    $course = new class($enrollments) extends Course
    {
        public function __construct(private object $enrollments) {}

        public function enrollments(): object
        {
            return $this->enrollments;
        }
    };
    $course->id = 5;

    $assignment = new Assignment;
    $assignment->course_id = 5;
    $assignment->submission_type = 'file';

    try {
        (new AssignmentSubmissionController)->store($request, $course, $assignment);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('enrollment')
            ->and($exception->errors()['enrollment'][0])
            ->toBe('Debes estar inscrito al curso para marcar lecciones.');
    }
});

it('forbids grading when user is neither admin nor teacher', function () {
    $request = Request::create('/classroom/courses/5/assignments/9/submissions/11', 'PATCH', [
        'score' => 90,
    ]);
    $request->setUserResolver(fn () => new class
    {
        public int $id = 3;

        public function hasRole(string $role): bool
        {
            return false;
        }
    });

    $course = new Course;
    $course->id = 5;

    $assignment = new Assignment;
    $assignment->id = 9;
    $assignment->course_id = 5;
    $assignment->max_score = 100;

    $submission = new AssignmentSubmission;
    $submission->assignment_id = 9;

    expect(fn () => (new AssignmentSubmissionController)->update($request, $course, $assignment, $submission))
        ->toThrow(HttpException::class);
});
