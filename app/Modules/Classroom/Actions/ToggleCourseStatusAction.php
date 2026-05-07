<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseStatusData;

class ToggleCourseStatusAction
{
    public function execute(Course $course, CourseStatusData $data): Course
    {
        $course->is_published = $data->isActive;
        $course->published_at = $data->isActive ? now() : null;
        $course->save();

        return $course;
    }
}
