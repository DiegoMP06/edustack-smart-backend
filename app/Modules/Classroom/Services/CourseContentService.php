<?php

namespace App\Modules\Classroom\Services;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Actions\UpdateCourseContentAction;
use App\Modules\Classroom\DTOs\CourseContentData;

class CourseContentService
{
    public function __construct(
        private UpdateCourseContentAction $updateContentAction,
    ) {}

    public function update(Course $course, CourseContentData $data): Course
    {
        return $this->updateContentAction->execute($course, $data);
    }
}
