<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreCourseDiscussionRequest;
use App\Http\Requests\Classroom\UpdateCourseDiscussionRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseLesson;
use Illuminate\Validation\ValidationException;

class CourseDiscussionController extends Controller
{
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

    public function store(StoreCourseDiscussionRequest $request, Course $course)
    {
        $data = $request->validated();

        $this->validateLessonBelongsToCourse($course, $data);

        $course->discussions()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('message', 'Discusion publicada.');
    }

    public function update(UpdateCourseDiscussionRequest $request, Course $course, CourseDiscussion $discussion)
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);

        $data = $request->validated();

        $this->validateLessonBelongsToCourse($course, $data);

        $discussion->update($data);

        return back()->with('message', 'Discusion actualizada.');
    }

    public function destroy(Course $course, CourseDiscussion $discussion)
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);

        $discussion->delete();

        return back()->with('message', 'Discusion eliminada.');
    }
}
