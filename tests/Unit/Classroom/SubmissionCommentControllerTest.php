<?php

use App\Http\Controllers\Classroom\SubmissionCommentController;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use App\Models\Classroom\SubmissionComment;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

uses(TestCase::class);

afterEach(function () {
    Mockery::close();
});

it('stores a comment for a submission', function () {
    session()->start();

    $httpRequest = Request::create('/classroom/courses/1/assignments/2/submissions/3/comments', 'POST', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/1/assignments/2',
    ]);
    $httpRequest->setLaravelSession(app('session')->driver());
    app()->instance('request', $httpRequest);

    $user = new class
    {
        public int $id = 9;
    };

    $request = Mockery::mock(Request::class);
    $request->shouldReceive('validate')->once()->andReturn([
        'content' => 'Buen trabajo en esta entrega.',
    ]);
    $request->shouldReceive('user')->andReturn($user);

    $course = new Course;
    $course->id = 1;

    $assignment = new Assignment;
    $assignment->id = 2;
    $assignment->course_id = 1;

    $commentsRelation = new class
    {
        public array $created = [];

        public function create(array $attributes): object
        {
            $this->created = $attributes;

            return (object) $attributes;
        }
    };

    $submission = new class($commentsRelation) extends AssignmentSubmission
    {
        public function __construct(private object $commentsRelation) {}

        public function comments(): object
        {
            return $this->commentsRelation;
        }
    };
    $submission->id = 3;
    $submission->assignment_id = 2;

    $response = (new SubmissionCommentController)->store($request, $course, $assignment, $submission);

    expect($commentsRelation->created)
        ->toMatchArray([
            'content' => 'Buen trabajo en esta entrega.',
            'user_id' => 9,
        ])
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Comentario agregado.');
});

it('forbids deleting a comment from another user when current user is not admin', function () {
    $request = Request::create('/classroom/courses/1/assignments/2/submissions/3/comments/4', 'DELETE');
    $request->setUserResolver(fn () => new class
    {
        public int $id = 10;

        public function hasRole(string $role): bool
        {
            return false;
        }
    });

    $course = new Course;
    $course->id = 1;

    $assignment = new Assignment;
    $assignment->id = 2;
    $assignment->course_id = 1;

    $submission = new AssignmentSubmission;
    $submission->id = 3;
    $submission->assignment_id = 2;

    $comment = new SubmissionComment;
    $comment->user_id = 99;
    $comment->assignment_submission_id = 3;

    expect(fn () => (new SubmissionCommentController)->destroy($request, $course, $assignment, $submission, $comment))
        ->toThrow(HttpException::class);
});
