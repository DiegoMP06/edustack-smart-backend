<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseData;
use Illuminate\Support\Facades\DB;

class CreateCourseAction
{
    /**
     * Persist a new model using DTO data.
     */
    public function execute(CourseData $data, int $userId): Course
    {
        return DB::transaction(function () use ($userId) {
            $course = Course::create([
                // Map DTO properties to model attributes.
                'user_id' => $userId,
            ]);

            // Example: $course->addMedia($data->file)->toMediaCollection('default');

            return $course;
        });
    }
}
