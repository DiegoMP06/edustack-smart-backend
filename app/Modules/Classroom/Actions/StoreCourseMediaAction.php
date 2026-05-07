<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseMediaData;

class StoreCourseMediaAction
{
    public function execute(Course $course, CourseMediaData $data): void
    {
        foreach ($data->images as $key) {
            $course->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }
    }
}
