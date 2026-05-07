<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseData;
use Illuminate\Support\Facades\DB;

class UpdateCourseAction
{
    /**
     * Update an existing model using DTO data.
     */
    public function execute(Course $course, CourseData $data): Course
    {
        return DB::transaction(function () use ($course) {
            $course->update([
                // Map DTO properties to model attributes.
            ]);

            return $course->load([]);
        });
    }
}
