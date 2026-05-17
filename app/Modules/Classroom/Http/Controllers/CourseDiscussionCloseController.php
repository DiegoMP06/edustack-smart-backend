<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Modules\Classroom\Application\UseCases\Command\ToggleDiscussionClosedAction;
use Illuminate\Http\RedirectResponse;

class CourseDiscussionCloseController extends Controller
{
    public function __construct(
        private ToggleDiscussionClosedAction $toggleDiscussionClosedAction,
    ) {}

    public function __invoke(Course $course, CourseDiscussion $discussion): RedirectResponse
    {
        if ($discussion->course_id !== $course->id) {
            abort(404);
        }

        $this->toggleDiscussionClosedAction->execute($discussion);

        return back()->with('message', 'Estado de la discusion actualizado.');
    }
}
