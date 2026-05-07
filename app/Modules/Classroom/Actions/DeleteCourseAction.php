<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use Illuminate\Support\Facades\DB;

class DeleteCourseAction
{
    /**
     * Delete the model in a transaction.
     */
    public function execute(Course $course): void
    {
        DB::transaction(function () use ($course) {
            // Example: $course->clearMediaCollection();
            $course->deleteOrFail();
        });
    }
}
