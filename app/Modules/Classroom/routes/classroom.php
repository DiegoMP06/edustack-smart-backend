<?php

use App\Modules\Classroom\Http\Controllers\AssignmentController;
use App\Modules\Classroom\Http\Controllers\AssignmentSubmissionController;
use App\Modules\Classroom\Http\Controllers\CourseAnnouncementController;
use App\Modules\Classroom\Http\Controllers\CourseContentController;
use App\Modules\Classroom\Http\Controllers\CourseController;
use App\Modules\Classroom\Http\Controllers\CourseDiscussionCloseController;
use App\Modules\Classroom\Http\Controllers\CourseDiscussionController;
use App\Modules\Classroom\Http\Controllers\CourseDiscussionReplyController;
use App\Modules\Classroom\Http\Controllers\CourseEnrollmentController;
use App\Modules\Classroom\Http\Controllers\CourseLessonContentController;
use App\Modules\Classroom\Http\Controllers\CourseLessonController;
use App\Modules\Classroom\Http\Controllers\CourseLessonStatusController;
use App\Modules\Classroom\Http\Controllers\CourseSectionController;
use App\Modules\Classroom\Http\Controllers\CourseStatusController;
use App\Modules\Classroom\Http\Controllers\CourseTeacherController;
use App\Modules\Classroom\Http\Controllers\LessonCompletionController;
use App\Modules\Classroom\Http\Controllers\SubmissionCommentController;
use Illuminate\Support\Facades\Route;

Route::resource('courses', CourseController::class);
Route::resource('courses.sections', CourseSectionController::class)
    ->parameters(['sections' => 'section'])
    ->only(['store', 'update', 'destroy']);
Route::resource('courses.lessons', CourseLessonController::class)
    ->parameters(['lessons' => 'lesson'])
    ->only(['create', 'store', 'edit', 'update', 'destroy']);
Route::resource('courses.assignments', AssignmentController::class)
    ->parameters(['assignments' => 'assignment'])
    ->only(['create', 'store', 'edit', 'update', 'destroy']);
Route::resource('courses.discussions', CourseDiscussionController::class)
    ->parameters(['discussions' => 'discussion'])
    ->only(['store', 'update', 'destroy']);
Route::resource('courses.announcements', CourseAnnouncementController::class)
    ->parameters(['announcements' => 'announcement'])
    ->only(['store', 'update', 'destroy']);
Route::resource('courses.teachers', CourseTeacherController::class)
    ->parameters(['teachers' => 'teacher'])
    ->only(['store', 'destroy']);

Route::post('courses/{course}/enrollments', [CourseEnrollmentController::class, 'store'])
    ->name('courses.enrollments.store');
Route::delete('courses/{course}/enrollments/{enrollment}', [CourseEnrollmentController::class, 'destroy'])
    ->name('courses.enrollments.destroy');
Route::patch('courses/{course}/status', CourseStatusController::class)
    ->name('courses.status');
Route::get('courses/{course}/content/edit', [CourseContentController::class, 'edit'])
    ->name('courses.content.edit');
Route::patch('courses/{course}/content', [CourseContentController::class, 'update'])
    ->name('courses.content.update');

Route::patch('courses/{course}/lessons/{lesson}/status', CourseLessonStatusController::class)
    ->name('courses.lessons.status');
Route::get('courses/{course}/lessons/{lesson}/content/edit', [CourseLessonContentController::class, 'edit'])
    ->name('courses.lessons.content.edit');
Route::patch('courses/{course}/lessons/{lesson}/content', [CourseLessonContentController::class, 'update'])
    ->name('courses.lessons.content.update');
Route::patch('courses/{course}/lessons/{lesson}/completion', LessonCompletionController::class)
    ->name('courses.lessons.completion');

Route::post('courses/{course}/assignments/{assignment}/submissions', [AssignmentSubmissionController::class, 'store'])
    ->name('courses.assignments.submissions.store');
Route::patch('courses/{course}/assignments/{assignment}/submissions/{submission}', [AssignmentSubmissionController::class, 'update'])
    ->name('courses.assignments.submissions.update');
Route::post('courses/{course}/assignments/{assignment}/submissions/{submission}/comments', [SubmissionCommentController::class, 'store'])
    ->name('courses.assignments.submissions.comments.store');
Route::delete('courses/{course}/assignments/{assignment}/submissions/{submission}/comments/{comment}', [SubmissionCommentController::class, 'destroy'])
    ->name('courses.assignments.submissions.comments.destroy');

Route::patch('courses/{course}/discussions/{discussion}/close', CourseDiscussionCloseController::class)
    ->name('courses.discussions.close');
Route::post('courses/{course}/discussions/{discussion}/replies', [CourseDiscussionReplyController::class, 'store'])
    ->name('courses.discussions.replies.store');
Route::delete('courses/{course}/discussions/{discussion}/replies/{reply}', [CourseDiscussionReplyController::class, 'destroy'])
    ->name('courses.discussions.replies.destroy');
Route::patch('courses/{course}/discussions/{discussion}/replies/{reply}/solution', [CourseDiscussionReplyController::class, 'markAsSolution'])
    ->name('courses.discussions.replies.solution');
