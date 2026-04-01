<?php

use App\Http\Controllers\Classroom\CourseDiscussionReplyController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseDiscussionReply;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

uses(TestCase::class);

it('defines expected validation rules for discussion replies', function () {
    $method = new ReflectionMethod(CourseDiscussionReplyController::class, 'rules');
    $method->setAccessible(true);

    $rules = $method->invoke(new CourseDiscussionReplyController, true);

    expect($rules['content'])->toContain('required')
        ->and($rules['content'])->toContain('array')
        ->and($rules['parent_id'])->toContain('exists:course_discussion_replies,id')
        ->and($rules['is_solution'])->toContain('boolean');
});

it('throws validation exception when trying to reply to a closed discussion', function () {
    $request = Request::create('/classroom/courses/4/discussions/7/replies', 'POST');
    $request->setUserResolver(fn () => new class
    {
        public int $id = 10;
    });

    $course = new Course;
    $course->id = 4;

    $discussion = new CourseDiscussion;
    $discussion->id = 7;
    $discussion->course_id = 4;
    $discussion->is_closed = true;

    try {
        (new CourseDiscussionReplyController)->store($request, $course, $discussion);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('discussion')
            ->and($exception->errors()['discussion'][0])
            ->toBe('Esta discusión está cerrada.');
    }
});

it('forbids deleting a reply from another user when current user is not admin', function () {
    $request = Request::create('/classroom/courses/4/discussions/7/replies/9', 'DELETE');
    $request->setUserResolver(fn () => new class
    {
        public int $id = 55;

        public function hasRole(string $role): bool
        {
            return false;
        }
    });

    $course = new Course;
    $course->id = 4;

    $discussion = new CourseDiscussion;
    $discussion->id = 7;
    $discussion->course_id = 4;

    $reply = new CourseDiscussionReply;
    $reply->id = 9;
    $reply->course_discussion_id = 7;
    $reply->user_id = 11;

    expect(fn () => (new CourseDiscussionReplyController)->destroy($request, $course, $discussion, $reply))
        ->toThrow(HttpException::class);
});

it('marks a reply as solution and clears previous solution flags', function () {
    session()->start();

    $request = Request::create('/classroom/courses/4/discussions/7/replies/9/solution', 'PATCH', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/4/discussions/7',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $request->setUserResolver(fn () => new class
    {
        public int $id = 22;

        public function hasRole(string $role): bool
        {
            return false;
        }
    });

    $repliesRelation = new class
    {
        public array $updated = [];

        public function update(array $attributes): int
        {
            $this->updated = $attributes;

            return 1;
        }
    };

    $course = new Course;
    $course->id = 4;

    $discussion = new class($repliesRelation) extends CourseDiscussion
    {
        public function __construct(private object $repliesRelation) {}

        public function replies(): object
        {
            return $this->repliesRelation;
        }
    };
    $discussion->id = 7;
    $discussion->course_id = 4;
    $discussion->user_id = 22;

    $reply = new class extends CourseDiscussionReply
    {
        public array $updatedData = [];

        public function update(array $attributes = [], array $options = []): bool
        {
            $this->updatedData = $attributes;

            return true;
        }
    };
    $reply->course_discussion_id = 7;

    $response = (new CourseDiscussionReplyController)->markAsSolution($request, $course, $discussion, $reply);

    expect($repliesRelation->updated)->toMatchArray(['is_solution' => false])
        ->and($reply->updatedData)->toMatchArray(['is_solution' => true])
        ->and($response->isRedirection())->toBeTrue()
        ->and(session('message'))->toBe('Respuesta marcada como solución.');
});
