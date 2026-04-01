<?php

use App\Http\Controllers\Classroom\CourseEnrollmentController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

uses(TestCase::class);

it('throws validation exception when enrollment window is closed', function () {
    $request = Request::create('/classroom/courses/1/enrollments', 'POST');
    $request->setUserResolver(fn () => new class
    {
        public int $id = 5;
    });

    $course = new Course;
    $course->enrollment_start_date = null;
    $course->enrollment_end_date = null;

    try {
        (new CourseEnrollmentController)->store($request, $course);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('enrollment')
            ->and($exception->errors()['enrollment'][0])
            ->toBe('Las inscripciones a este curso no están abiertas.');
    }
});

it('cancels enrollment and expires pending payment', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/enrollments/2', 'DELETE', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/1',
    ]);
    $request->setLaravelSession(app('session')->driver());
    $user = new class
    {
        public int $id = 7;

        public function hasRole(string $role): bool
        {
            return false;
        }
    };
    $request->setUserResolver(fn () => $user);

    $course = new Course;
    $course->id = 1;

    $pendingPayment = new class
    {
        public string $status = 'pending';

        public array $updated = [];

        public function update(array $data): bool
        {
            $this->updated = $data;

            return true;
        }
    };

    $enrollment = new class($pendingPayment) extends CourseEnrollment
    {
        public array $updated = [];

        public function __construct(public object $payment) {}

        public function update(array $attributes = [], array $options = []): bool
        {
            $this->updated = $attributes;

            return true;
        }
    };
    $enrollment->course_id = 1;
    $enrollment->user_id = 7;

    expect($request->user())->not->toBeNull();

    $response = (new CourseEnrollmentController)->destroy($request, $course, $enrollment);

    expect($enrollment->updated)
        ->toHaveKey('status')
        ->and($enrollment->updated['status'])->toBe('dropped')
        ->and($enrollment->updated)->toHaveKey('dropped_at')
        ->and($pendingPayment->updated['status'])->toBe('expired')
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Inscripción cancelada.');
});

it('forbids enrollment cancellation by other non admin users', function () {
    $request = Request::create('/classroom/courses/1/enrollments/2', 'DELETE');
    $request->setUserResolver(fn () => new class
    {
        public int $id = 99;

        public function hasRole(string $role): bool
        {
            return false;
        }
    });

    $course = new Course;
    $course->id = 1;

    $enrollment = new CourseEnrollment;
    $enrollment->course_id = 1;
    $enrollment->user_id = 7;

    expect(fn () => (new CourseEnrollmentController)->destroy($request, $course, $enrollment))
        ->toThrow(HttpException::class);
});
