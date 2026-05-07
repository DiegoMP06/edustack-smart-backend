<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseContentData;

class UpdateCourseContentAction
{
    public function execute(Course $course, CourseContentData $data): Course
    {
        $course->content = $data->content;
        $course->save();

        return $course;
    }
}
