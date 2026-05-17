<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseDiscussion;
use App\Models\Classroom\CourseDiscussionReply;
use App\Modules\Classroom\Application\DTOs\CourseDiscussionReplyFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateDiscussionReplyAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteDiscussionReplyAction;
use App\Modules\Classroom\Application\UseCases\Command\ToggleDiscussionReplySolutionAction;
use App\Modules\Classroom\Http\Requests\StoreCourseDiscussionReplyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourseDiscussionReplyController extends Controller
{
    public function __construct(
        private CreateDiscussionReplyAction $createDiscussionReplyAction,
        private DeleteDiscussionReplyAction $deleteDiscussionReplyAction,
        private ToggleDiscussionReplySolutionAction $toggleDiscussionReplySolutionAction,
    ) {}

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

    public function store(StoreCourseDiscussionReplyRequest $request, Course $course, CourseDiscussion $discussion): RedirectResponse
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

        $formData = CourseDiscussionReplyFormData::from($data);
        $this->createDiscussionReplyAction->execute($discussion, $request->user(), $formData);

        return back()->with('message', 'Respuesta publicada.');
    }

    public function destroy(Request $request, Course $course, CourseDiscussion $discussion, CourseDiscussionReply $reply): RedirectResponse
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);
        $this->ensureReplyBelongsToDiscussion($discussion, $reply);

        if ($reply->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $this->deleteDiscussionReplyAction->execute($reply);

        return back()->with('message', 'Respuesta eliminada.');
    }

    public function markAsSolution(Request $request, Course $course, CourseDiscussion $discussion, CourseDiscussionReply $reply): RedirectResponse
    {
        $this->ensureDiscussionBelongsToCourse($course, $discussion);
        $this->ensureReplyBelongsToDiscussion($discussion, $reply);

        if ($discussion->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        DB::transaction(function () use ($discussion, $reply) {
            $discussion->replies()->update(['is_solution' => false]);
            $this->toggleDiscussionReplySolutionAction->execute($reply);
        });

        return back()->with('message', 'Respuesta marcada como solución.');
    }
}
