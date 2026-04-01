<?php

use App\Http\Controllers\Classroom\AssignmentController;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\Course;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

afterEach(function () {
    Mockery::close();
});

it('defines expected validation rules for assignments', function () {
    $method = new ReflectionMethod(AssignmentController::class, 'rules');
    $method->setAccessible(true);

    $storeRules = $method->invoke(new AssignmentController, true);
    $updateRules = $method->invoke(new AssignmentController, false);

    expect($storeRules['submission_type'])->toContain('in:file,text,url,form,mixed')
        ->and($storeRules['due_date'])->toContain('after:now')
        ->and($updateRules['due_date'])->not->toContain('after:now')
        ->and($storeRules['passing_score'])->toContain('lte:max_score')
        ->and($storeRules['course_lesson_id'])->toContain('exists:course_lessons,id');
});

it('throws validation exception when selected lesson does not belong to the course', function () {
    $method = new ReflectionMethod(AssignmentController::class, 'validateLessonBelongsToCourse');
    $method->setAccessible(true);

    $course = new Course;
    $course->id = 77;

    $query = Mockery::mock();
    $query->shouldReceive('where')->once()->with('course_id', 77)->andReturnSelf();
    $query->shouldReceive('doesntExist')->once()->andReturn(true);

    $alias = Mockery::mock('alias:App\\Models\\Classroom\\CourseLesson');
    $alias->shouldReceive('where')->once()->with('id', 12)->andReturn($query);

    try {
        $method->invoke(new AssignmentController, $course, ['course_lesson_id' => 12]);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('course_lesson_id')
            ->and($exception->errors()['course_lesson_id'][0])
            ->toBe('La lección no pertenece a este curso.');
    }
});

it('throws validation exception when deleting assignment with submissions', function () {
    $course = new Course;
    $course->id = 4;

    $submissions = new class
    {
        public function exists(): bool
        {
            return true;
        }
    };

    $assignment = new class($submissions) extends Assignment
    {
        public function __construct(private object $submissions) {}

        public function submissions(): object
        {
            return $this->submissions;
        }
    };
    $assignment->course_id = 4;

    try {
        (new AssignmentController)->destroy($course, $assignment);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('assignment')
            ->and($exception->errors()['assignment'][0])
            ->toBe('No puedes eliminar una tarea con entregas registradas.');
    }
});
