<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Modules\Classroom\Services\CourseStatusService;

class CourseStatusController extends Controller
{
    public function __construct(
        private CourseStatusService $statusService,
    ) {}

    /**
     * Toggle the model status flag.
     */
    public function __invoke(Course $course)
    {
        $this->authorize('update', $course);

        $this->statusService->toggle($course);

        return back()->with('message', 'Course status updated successfully.');
    }
}
