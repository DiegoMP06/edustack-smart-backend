<?php

use App\Models\Classroom\Assignment;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseEnrollment;
use App\Models\Forms\Form;
use App\Models\Forms\FormQuestion;
use App\Models\Forms\FormResponse;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tests\TestCase;

uses(TestCase::class);

it('defines core classroom model relations and media behavior', function () {
    $course = new Course;

    expect($course->status())->toBeInstanceOf(BelongsTo::class)
        ->and($course->category())->toBeInstanceOf(BelongsTo::class)
        ->and($course->sections())->toBeInstanceOf(HasMany::class)
        ->and($course->lessons())->toBeInstanceOf(HasMany::class)
        ->and($course->resources())->toBeInstanceOf(MorphMany::class)
        ->and(method_exists($course, 'addMediaFromRequest'))->toBeTrue();
});

it('defines core classroom workflow relations', function () {
    $assignment = new Assignment;
    $submission = new AssignmentSubmission;
    $discussion = new CourseDiscussion;
    $enrollment = new CourseEnrollment;

    expect($assignment->course())->toBeInstanceOf(BelongsTo::class)
        ->and($assignment->submissions())->toBeInstanceOf(HasMany::class)
        ->and($submission->assignment())->toBeInstanceOf(BelongsTo::class)
        ->and($submission->comments())->toBeInstanceOf(HasMany::class)
        ->and($discussion->replies())->toBeInstanceOf(HasMany::class)
        ->and($enrollment->course())->toBeInstanceOf(BelongsTo::class);
});

it('defines core form builder model relations', function () {
    $form = new Form;
    $question = new FormQuestion;
    $response = new FormResponse;

    expect($form->type())->toBeInstanceOf(BelongsTo::class)
        ->and($form->sections())->toBeInstanceOf(HasMany::class)
        ->and($form->questions())->toBeInstanceOf(HasMany::class)
        ->and($form->logicRules())->toBeInstanceOf(HasMany::class)
        ->and($response->answers())->toBeInstanceOf(HasMany::class)
        ->and($question->options())->toBeInstanceOf(HasMany::class);
});

it('adds missing user ownership relations for classroom and forms', function () {
    $user = new User;

    expect($user->courses())->toBeInstanceOf(HasMany::class)
        ->and($user->forms())->toBeInstanceOf(HasMany::class);
});
