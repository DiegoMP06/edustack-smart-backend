<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreCourseDiscussionReplyRequest;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseDiscussionReply;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseDiscussionReplyController extends Controller
{
    private function ensureDiscussionBelongsToCourse(Course $course, CourseDiscussion $discussion): void
    {
        if ($discussion->course_id !== $course->id) {
            abort(404);
        }
    }

    private function ensureReplyBelongsToDiscussion(CourseDiscussion $discussion, CourseDiscussionReply $reply): void
    {
        if ($reply->course_discussion_id !== $discussion->id) {
            abort(404);
        }
    }

    public function store(StoreCourseDiscussionReplyRequest $request, Course $course, CourseDiscussion $discussion)
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);

        if ($discussion->is_closed) {
            throw ValidationException::withMessages([
                'discussion' => 'Esta discusión está cerrada.',
            ]);
        }

        $data = $request->validated();

        if (
            $data['parent_id']
            && CourseDiscussionReply::where('id', $data['parent_id'])
                ->where('course_discussion_id', $discussion->id)
                ->doesntExist()
        ) {
            throw ValidationException::withMessages([
                'parent_id' => 'Respuesta padre inválida.',
            ]);
        }

        $discussion->replies()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('message', 'Respuesta publicada.');
    }

    public function destroy(Request $request, Course $course, CourseDiscussion $discussion, CourseDiscussionReply $reply)
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);
        $this->ensureReplyBelongsToDiscussion($discussion, $reply);

        if ($reply->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $reply->delete();

        return back()->with('message', 'Respuesta eliminada.');
    }

    public function markAsSolution(Request $request, Course $course, CourseDiscussion $discussion, CourseDiscussionReply $reply)
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);
        $this->ensureReplyBelongsToDiscussion($discussion, $reply);

        if ($discussion->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $discussion->replies()->update(['is_solution' => false]);
        $reply->update(['is_solution' => true]);

        return back()->with('message', 'Respuesta marcada como solución.');
    }
}
