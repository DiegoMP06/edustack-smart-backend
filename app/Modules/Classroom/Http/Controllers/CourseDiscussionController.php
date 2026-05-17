<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseLesson;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateDiscussionAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteDiscussionAction;
use App\Modules\Classroom\Application\UseCases\Command\UpdateDiscussionAction;
use App\Modules\Classroom\Http\Requests\StoreCourseDiscussionRequest;
use App\Modules\Classroom\Http\Requests\UpdateCourseDiscussionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CourseDiscussionController extends Controller
{
    public function __construct(
        private CreateDiscussionAction $createDiscussionAction,
        private UpdateDiscussionAction $updateDiscussionAction,
        private DeleteDiscussionAction $deleteDiscussionAction,
    ) {}

    private function ensureDiscussionBelongsToCourse(Course $course, CourseDiscussion $discussion): void
    {
        if ($discussion->course_id !== $course->id) {
            abort(404);
        }
    }

    private function validateLessonBelongsToCourse(Course $course, array $data): void
    {
        if (
            $data['course_lesson_id'] !== null
            && CourseLesson::where('id', $data['course_lesson_id'])->where('course_id', $course->id)->doesntExist()
        ) {
            throw ValidationException::withMessages([
                'course_lesson_id' => 'La leccion no pertenece a este curso.',
            ]);
        }
    }

    public function store(StoreCourseDiscussionRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $this->validateLessonBelongsToCourse($course, $data);

        $formData = CourseDiscussionFormData::from($data);
        $this->createDiscussionAction->execute($course, $request->user(), $formData);

        return back()->with('message', 'Discusion publicada.');
    }

    public function update(UpdateCourseDiscussionRequest $request, Course $course, CourseDiscussion $discussion): RedirectResponse
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);

        $data = $request->validated();
        $this->validateLessonBelongsToCourse($course, $data);

        $formData = CourseDiscussionFormData::from($data);
        $this->updateDiscussionAction->execute($discussion, $formData);

        return back()->with('message', 'Discusion actualizada.');
    }

    public function destroy(Course $course, CourseDiscussion $discussion): RedirectResponse
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);

        $this->deleteDiscussionAction->execute($discussion);

        return back()->with('message', 'Discusion eliminada.');
    }
}
