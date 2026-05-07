<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetCourseAction
{
    /**
     * Retrieve a model by its primary key.
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id): Course
    {
        $course = Course::find($id);

        if (! $course) {
            throw new ModelNotFoundException("The record with ID {$id} was not found.");
        }

        // If you prefer returning a DTO, map it here and adjust the return type.

        return $course;
    }
}
