<?php

namespace App\Modules\Classroom\Services;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Actions\ToggleCourseStatusAction;
use App\Modules\Classroom\DTOs\CourseStatusData;

class CourseStatusService
{
    public function __construct(
        private ToggleCourseStatusAction $toggleStatusAction,
    ) {}

    public function toggle(Course $course): Course
    {
        $data = CourseStatusData::fromModel($course);

        return $this->toggleStatusAction->execute($course, $data);
    }
}
