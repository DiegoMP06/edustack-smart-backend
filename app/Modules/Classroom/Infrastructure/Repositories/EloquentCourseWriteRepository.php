<?php

namespace App\Modules\Classroom\Infrastructure\Repositories;

use App\Models\Classroom\Assignment;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseAnnouncement;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseDiscussionReply;
use App\Models\Classroom\CourseEnrollment;
use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\CourseSection;
use App\Models\Classroom\CourseTeacher;
use App\Models\Classroom\LessonCompletion;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\AssignmentFormData;
use App\Modules\Classroom\Application\DTOs\CourseAnnouncementFormData;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionFormData;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionReplyFormData;
use App\Modules\Classroom\Application\DTOs\CourseFormData;
use App\Modules\Classroom\Application\DTOs\CourseLessonFormData;
use App\Modules\Classroom\Application\DTOs\CourseSectionFormData;
use App\Modules\Classroom\Application\DTOs\CourseTeacherFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class EloquentCourseWriteRepository implements CourseWriteRepository
{
    // Course
    public function createForUser(User $user, CourseFormData $data): Course
    {
        return $user->courses()->create([
            'name' => $data->name,
            'summary' => $data->summary,
            'content' => [],
            'code' => $data->code,
            'credits' => $data->credits,
            'period' => $data->period,
            'price' => $data->price,
            'is_free' => $data->is_free,
            'capacity' => $data->capacity,
            'course_status_id' => $data->course_status_id,
            'course_category_id' => $data->course_category_id,
            'start_date' => $data->start_date,
            'end_date' => $data->end_date,
            'enrollment_start_date' => $data->enrollment_start_date,
            'enrollment_end_date' => $data->enrollment_end_date,
            'is_published' => $data->is_published,
        ]);
    }

    public function update(Course $course, CourseFormData $data): Course
    {
        $course->update([
            'name' => $data->name,
            'summary' => $data->summary,
            'code' => $data->code,
            'credits' => $data->credits,
            'period' => $data->period,
            'price' => $data->price,
            'is_free' => $data->is_free,
            'capacity' => $data->capacity,
            'course_status_id' => $data->course_status_id,
            'course_category_id' => $data->course_category_id,
            'start_date' => $data->start_date,
            'end_date' => $data->end_date,
            'enrollment_start_date' => $data->enrollment_start_date,
            'enrollment_end_date' => $data->enrollment_end_date,
            'is_published' => $data->is_published,
        ]);

        return $course;
    }

    public function delete(Course $course): void
    {
        $course->deleteOrFail();
    }

    public function updateContent(Course $course, ModelContentFormData $data): Course
    {
        $course->content = $data->content;
        $course->save();

        return $course;
    }

    public function togglePublished(Course $course): Course
    {
        $course->is_published = ! $course->is_published;
        $course->save();

        return $course;
    }

    // CourseSection
    public function createSection(Course $course, CourseSectionFormData $data): CourseSection
    {
        return $course->sections()->create([
            'name' => $data->name,
            'description' => $data->description,
            'order' => $data->order,
            'is_published' => $data->is_published,
        ]);
    }

    public function updateSection(CourseSection $section, CourseSectionFormData $data): CourseSection
    {
        $section->update([
            'name' => $data->name,
            'description' => $data->description,
            'order' => $data->order,
            'is_published' => $data->is_published,
        ]);

        return $section;
    }

    public function deleteSection(CourseSection $section): void
    {
        $section->deleteOrFail();
    }

    // CourseLesson
    public function createLesson(Course $course, CourseLessonFormData $data): CourseLesson
    {
        return $course->lessons()->create([
            'name' => $data->name,
            'summary' => $data->summary,
            'content' => [],
            'type' => $data->type,
            'video_url' => $data->video_url,
            'video_duration_seconds' => $data->video_duration_seconds,
            'order' => $data->order,
            'estimated_minutes' => $data->estimated_minutes,
            'is_published' => $data->is_published,
            'is_preview' => $data->is_preview,
            'course_section_id' => $data->course_section_id,
        ]);
    }

    public function updateLesson(CourseLesson $lesson, CourseLessonFormData $data): CourseLesson
    {
        $lesson->update([
            'name' => $data->name,
            'summary' => $data->summary,
            'type' => $data->type,
            'video_url' => $data->video_url,
            'video_duration_seconds' => $data->video_duration_seconds,
            'order' => $data->order,
            'estimated_minutes' => $data->estimated_minutes,
            'is_published' => $data->is_published,
            'is_preview' => $data->is_preview,
            'course_section_id' => $data->course_section_id,
        ]);

        return $lesson;
    }

    public function deleteLesson(CourseLesson $lesson): void
    {
        $lesson->deleteOrFail();
    }

    public function updateLessonContent(CourseLesson $lesson, ModelContentFormData $data): CourseLesson
    {
        $lesson->content = $data->content;
        $lesson->save();

        return $lesson;
    }

    public function toggleLessonPublished(CourseLesson $lesson): CourseLesson
    {
        $lesson->is_published = ! $lesson->is_published;
        $lesson->save();

        return $lesson;
    }

    // LessonCompletion
    public function completeLesson(User $user, CourseLesson $lesson): LessonCompletion
    {
        return $lesson->completions()->firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $lesson->course_id,
        ], [
            'completed_at' => now(),
        ]);
    }

    public function uncompleteLesson(User $user, CourseLesson $lesson): void
    {
        $lesson->completions()->where('user_id', $user->id)->delete();
    }

    // CourseAnnouncement
    public function createAnnouncement(Course $course, User $author, CourseAnnouncementFormData $data): CourseAnnouncement
    {
        return $course->announcements()->create([
            'title' => $data->title,
            'content' => $data->content,
            'is_pinned' => $data->is_pinned,
            'notify_students' => $data->notify_students,
            'published_at' => $data->published_at,
            'user_id' => $author->id,
        ]);
    }

    public function updateAnnouncement(CourseAnnouncement $announcement, CourseAnnouncementFormData $data): CourseAnnouncement
    {
        $announcement->update([
            'title' => $data->title,
            'content' => $data->content,
            'is_pinned' => $data->is_pinned,
            'notify_students' => $data->notify_students,
            'published_at' => $data->published_at,
        ]);

        return $announcement;
    }

    public function deleteAnnouncement(CourseAnnouncement $announcement): void
    {
        $announcement->deleteOrFail();
    }

    // CourseDiscussion
    public function createDiscussion(Course $course, User $author, CourseDiscussionFormData $data): CourseDiscussion
    {
        return $course->discussions()->create([
            'title' => $data->title,
            'content' => $data->content,
            'is_pinned' => $data->is_pinned,
            'course_lesson_id' => $data->course_lesson_id,
            'user_id' => $author->id,
        ]);
    }

    public function updateDiscussion(CourseDiscussion $discussion, CourseDiscussionFormData $data): CourseDiscussion
    {
        $discussion->update([
            'title' => $data->title,
            'content' => $data->content,
            'is_pinned' => $data->is_pinned,
            'course_lesson_id' => $data->course_lesson_id,
        ]);

        return $discussion;
    }

    public function deleteDiscussion(CourseDiscussion $discussion): void
    {
        $discussion->deleteOrFail();
    }

    public function toggleClosed(CourseDiscussion $discussion): CourseDiscussion
    {
        $discussion->is_closed = ! $discussion->is_closed;
        $discussion->save();

        return $discussion;
    }

    // CourseDiscussionReply
    public function createDiscussionReply(CourseDiscussion $discussion, User $author, CourseDiscussionReplyFormData $data): CourseDiscussionReply
    {
        return $discussion->replies()->create([
            'content' => $data->content,
            'parent_id' => $data->parent_id,
            'is_solution' => $data->is_solution,
            'user_id' => $author->id,
        ]);
    }

    public function deleteDiscussionReply(CourseDiscussionReply $reply): void
    {
        $reply->deleteOrFail();
    }

    public function toggleSolution(CourseDiscussionReply $reply): CourseDiscussionReply
    {
        $reply->is_solution = ! $reply->is_solution;
        $reply->save();

        return $reply;
    }

    // CourseTeacher
    public function addTeacher(Course $course, CourseTeacherFormData $data): CourseTeacher
    {
        return $course->teachers()->firstOrCreate([
            'user_id' => $data->user_id,
        ], [
            'role' => $data->role,
        ]);
    }

    public function removeTeacher(Course $course, int $userId): void
    {
        $course->teachers()->where('user_id', $userId)->delete();
    }

    // CourseEnrollment
    public function enrollUser(Course $course, User $user): CourseEnrollment
    {
        return $course->enrollments()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'status' => \App\Enums\Classroom\CourseEnrollmentStatus::ENROLLED,
            'enrolled_at' => now(),
        ]);
    }

    public function dropUser(Course $course, User $user): void
    {
        $course->enrollments()->where('user_id', $user->id)->delete();
    }

    // Assignment
    public function createAssignment(Course $course, User $author, AssignmentFormData $data): Assignment
    {
        return $course->assignments()->create([
            'name' => $data->name,
            'summary' => $data->summary,
            'instructions' => $data->instructions,
            'max_score' => $data->max_score,
            'passing_score' => $data->passing_score,
            'allow_late_submissions' => $data->allow_late_submissions,
            'max_attempts' => $data->max_attempts,
            'submission_type' => $data->submission_type,
            'is_published' => $data->is_published,
            'due_date' => $data->due_date,
            'available_from' => $data->available_from,
            'course_lesson_id' => $data->course_lesson_id,
            'user_id' => $author->id,
        ]);
    }

    public function updateAssignment(Assignment $assignment, AssignmentFormData $data): Assignment
    {
        $assignment->update([
            'name' => $data->name,
            'summary' => $data->summary,
            'instructions' => $data->instructions,
            'max_score' => $data->max_score,
            'passing_score' => $data->passing_score,
            'allow_late_submissions' => $data->allow_late_submissions,
            'max_attempts' => $data->max_attempts,
            'submission_type' => $data->submission_type,
            'is_published' => $data->is_published,
            'due_date' => $data->due_date,
            'available_from' => $data->available_from,
            'course_lesson_id' => $data->course_lesson_id,
        ]);

        return $assignment;
    }

    public function deleteAssignment(Assignment $assignment): void
    {
        $assignment->deleteOrFail();
    }
}
