<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Modules\Classroom\Application\UseCases\Command\ToggleCoursePublishedAction;
use Illuminate\Http\RedirectResponse;

class CourseStatusController extends Controller
{
    public function __construct(
        private ToggleCoursePublishedAction $toggleCoursePublishedAction,
    ) {}

    public function __invoke(Course $course): RedirectResponse
    {
        $this->toggleCoursePublishedAction->execute($course);

        return back()->with('message', 'Estado del curso actualizado.');
    }
}
