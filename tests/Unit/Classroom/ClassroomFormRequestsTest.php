<?php

use App\Http\Requests\Classroom\StoreAssignmentRequest;
use App\Http\Requests\Classroom\StoreAssignmentSubmissionRequest;
use App\Http\Requests\Classroom\StoreCourseAnnouncementRequest;
use App\Http\Requests\Classroom\StoreCourseDiscussionReplyRequest;
use App\Http\Requests\Classroom\StoreCourseDiscussionRequest;
use App\Http\Requests\Classroom\StoreCourseLessonRequest;
use App\Http\Requests\Classroom\StoreCourseRequest;
use App\Http\Requests\Classroom\StoreCourseSectionRequest;
use App\Http\Requests\Classroom\StoreCourseTeacherRequest;
use App\Http\Requests\Classroom\StoreSubmissionCommentRequest;
use App\Http\Requests\Classroom\UpdateAssignmentRequest;
use App\Http\Requests\Classroom\UpdateAssignmentSubmissionRequest;
use App\Http\Requests\Classroom\UpdateCourseAnnouncementRequest;
use App\Http\Requests\Classroom\UpdateCourseContentRequest;
use App\Http\Requests\Classroom\UpdateCourseDiscussionRequest;
use App\Http\Requests\Classroom\UpdateCourseLessonContentRequest;
use App\Http\Requests\Classroom\UpdateCourseLessonRequest;
use App\Http\Requests\Classroom\UpdateCourseRequest;
use App\Http\Requests\Classroom\UpdateCourseSectionRequest;
use App\Models\Classroom\Assignment;
use Illuminate\Foundation\Http\FormRequest;
use Tests\TestCase;

uses(TestCase::class);

it('classroom form requests authorize and extend form request', function (string $className) {
    $request = new $className;

    expect($request)->toBeInstanceOf(FormRequest::class)
        ->and($request->authorize())->toBeTrue();
})->with([
    StoreCourseRequest::class,
    UpdateCourseRequest::class,
    UpdateCourseContentRequest::class,
    StoreCourseSectionRequest::class,
    UpdateCourseSectionRequest::class,
    StoreCourseLessonRequest::class,
    UpdateCourseLessonRequest::class,
    UpdateCourseLessonContentRequest::class,
    StoreCourseAnnouncementRequest::class,
    UpdateCourseAnnouncementRequest::class,
    StoreCourseDiscussionRequest::class,
    UpdateCourseDiscussionRequest::class,
    StoreCourseDiscussionReplyRequest::class,
    StoreCourseTeacherRequest::class,
    StoreAssignmentRequest::class,
    UpdateAssignmentRequest::class,
    StoreAssignmentSubmissionRequest::class,
    UpdateAssignmentSubmissionRequest::class,
    StoreSubmissionCommentRequest::class,
]);

it('builds submission request rules from assignment type and score limits', function () {
    $assignment = new Assignment;
    $assignment->submission_type = 'text';
    $assignment->max_score = 100;

    $route = new class($assignment)
    {
        public function __construct(private Assignment $assignment) {}

        public function parameter(string $name): Assignment
        {
            return $this->assignment;
        }
    };

    $storeRequest = new StoreAssignmentSubmissionRequest;
    $storeRequest->setRouteResolver(fn (): object => $route);

    $updateRequest = new UpdateAssignmentSubmissionRequest;
    $updateRequest->setRouteResolver(fn (): object => $route);

    expect($storeRequest->rules())
        ->toHaveKey('text_content')
        ->and($updateRequest->rules()['score'])->toContain('max:100');
});
