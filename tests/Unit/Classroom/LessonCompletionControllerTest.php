<?php

use App\Http\Controllers\Classroom\LessonCompletionController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

afterEach(function () {
    Mockery::close();
});

it('throws validation exception when user is not actively enrolled', function () {
    $request = Request::create('/classroom/courses/1/lessons/2/complete', 'PATCH');
    $request->setUserResolver(fn ($guard = null) => new class
    {
        public int $id = 5;
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
    $course->id = 1;

    $lesson = new CourseLesson;
    $lesson->id = 2;
    $lesson->course_id = 1;

    try {
        (new LessonCompletionController)->__invoke($request, $course, $lesson);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('enrollment')
            ->and($exception->errors()['enrollment'][0])
            ->toBe('Debes estar inscrito al curso para marcar lecciones.');
    }
});

it('creates completion and marks enrollment as completed at 100 progress', function () {
    session()->start();

    $httpRequest = Request::create('/classroom/courses/3/lessons/22/complete', 'PATCH', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/3/lessons/22',
    ]);
    app()->instance('request', $httpRequest);

    $user = new class
    {
        public int $id = 5;
    };

    $request = Mockery::mock(Request::class);
    $request->shouldReceive('user')->andReturn($user);

    $activeCheck = new class
    {
        public function where(string $column, mixed $value): self
        {
            return $this;
        }

        public function exists(): bool
        {
            return true;
        }
    };

    $progressUpdate = new class
    {
        public array $updated = [];

        public function where(string $column, mixed $value): self
        {
            return $this;
        }

        public function update(array $data): int
        {
            $this->updated = $data;

            return 1;
        }
    };

    $completionUpdate = new class
    {
        public array $updated = [];

        public function where(string $column, mixed $value): self
        {
            return $this;
        }

        public function update(array $data): int
        {
            $this->updated = $data;

            return 1;
        }
    };

    $lessons = new class
    {
        public function where(string $column, bool $value): self
        {
            return $this;
        }

        public function count(): int
        {
            return 4;
        }
    };

    $course = new class($activeCheck, $progressUpdate, $completionUpdate, $lessons) extends Course
    {
        private int $enrollmentsCall = 0;

        public function __construct(
            private object $activeCheck,
            private object $progressUpdate,
            private object $completionUpdate,
            private object $lessons
        ) {}

        public function enrollments(): object
        {
            $this->enrollmentsCall++;

            return match ($this->enrollmentsCall) {
                1 => $this->activeCheck,
                2 => $this->progressUpdate,
                default => $this->completionUpdate,
            };
        }

        public function lessons(): object
        {
            return $this->lessons;
        }
    };
    $course->id = 3;

    $lesson = new CourseLesson;
    $lesson->id = 22;
    $lesson->course_id = 3;

    $alias = Mockery::mock('alias:App\\Models\\Classroom\\LessonCompletion');

    $firstQuery = Mockery::mock();
    $firstQuery->shouldReceive('where')->once()->with('course_lesson_id', 22)->andReturnSelf();
    $firstQuery->shouldReceive('first')->once()->andReturn(null);

    $countQuery = Mockery::mock();
    $countQuery->shouldReceive('where')->once()->with('course_id', 3)->andReturnSelf();
    $countQuery->shouldReceive('count')->once()->andReturn(4);

    $alias->shouldReceive('where')->once()->with('user_id', 5)->andReturn($firstQuery);
    $alias->shouldReceive('create')->once()->with(Mockery::on(function (array $payload): bool {
        return $payload['user_id'] === 5
            && $payload['course_lesson_id'] === 22
            && $payload['course_id'] === 3
            && array_key_exists('completed_at', $payload);
    }));
    $alias->shouldReceive('where')->once()->with('user_id', 5)->andReturn($countQuery);

    $response = (new LessonCompletionController)->__invoke($request, $course, $lesson);

    expect($progressUpdate->updated['progress'])->toBe(100)
        ->and($completionUpdate->updated['status'])->toBe('completed')
        ->and($completionUpdate->updated)->toHaveKey('completed_at')
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Lección completada.');
});
