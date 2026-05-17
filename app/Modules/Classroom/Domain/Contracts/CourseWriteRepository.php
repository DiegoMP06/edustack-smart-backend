<?php

namespace App\Modules\Classroom\Domain\Contracts;

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
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

interface CourseWriteRepository
{
    // Course
    public function createForUser(User $user, CourseFormData $data): Course;

    public function update(Course $course, CourseFormData $data): Course;

    public function delete(Course $course): void;

    public function updateContent(Course $course, ModelContentFormData $data): Course;

    public function togglePublished(Course $course): Course;

    // CourseSection
    public function createSection(Course $course, CourseSectionFormData $data): CourseSection;

    public function updateSection(CourseSection $section, CourseSectionFormData $data): CourseSection;

    public function deleteSection(CourseSection $section): void;

    // CourseLesson
    public function createLesson(Course $course, CourseLessonFormData $data): CourseLesson;

    public function updateLesson(CourseLesson $lesson, CourseLessonFormData $data): CourseLesson;

    public function deleteLesson(CourseLesson $lesson): void;

    public function updateLessonContent(CourseLesson $lesson, ModelContentFormData $data): CourseLesson;

    public function toggleLessonPublished(CourseLesson $lesson): CourseLesson;

    // LessonCompletion
    public function completeLesson(User $user, CourseLesson $lesson): LessonCompletion;

    public function uncompleteLesson(User $user, CourseLesson $lesson): void;

    // CourseAnnouncement
    public function createAnnouncement(Course $course, User $author, CourseAnnouncementFormData $data): CourseAnnouncement;

    public function updateAnnouncement(CourseAnnouncement $announcement, CourseAnnouncementFormData $data): CourseAnnouncement;

    public function deleteAnnouncement(CourseAnnouncement $announcement): void;

    // CourseDiscussion
    public function createDiscussion(Course $course, User $author, CourseDiscussionFormData $data): CourseDiscussion;

    public function updateDiscussion(CourseDiscussion $discussion, CourseDiscussionFormData $data): CourseDiscussion;

    public function deleteDiscussion(CourseDiscussion $discussion): void;

    public function toggleClosed(CourseDiscussion $discussion): CourseDiscussion;

    // CourseDiscussionReply
    public function createDiscussionReply(CourseDiscussion $discussion, User $author, CourseDiscussionReplyFormData $data): CourseDiscussionReply;

    public function deleteDiscussionReply(CourseDiscussionReply $reply): void;

    public function toggleSolution(CourseDiscussionReply $reply): CourseDiscussionReply;

    // CourseTeacher
    public function addTeacher(Course $course, CourseTeacherFormData $data): CourseTeacher;

    public function removeTeacher(Course $course, int $userId): void;

    // CourseEnrollment
    public function enrollUser(Course $course, User $user): CourseEnrollment;

    public function dropUser(Course $course, User $user): void;

    // Assignment
    public function createAssignment(Course $course, User $author, AssignmentFormData $data): Assignment;

    public function updateAssignment(Assignment $assignment, AssignmentFormData $data): Assignment;

    public function deleteAssignment(Assignment $assignment): void;
}
