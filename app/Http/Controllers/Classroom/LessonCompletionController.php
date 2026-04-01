<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseLesson;
use App\Models\Classroom\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LessonCompletionController extends Controller
{
    public function __invoke(Request $request, Course $course, CourseLesson $lesson)
    {
        abort_if($lesson->course_id !== $course->id, 404);

        if (! $course->enrollments()->where('user_id', $request->user()->id)->where('status', 'active')->exists()) {
            throw ValidationException::withMessages([
                'enrollment' => 'Debes estar inscrito al curso para marcar lecciones.',
            ]);
        }

        $existing = LessonCompletion::where('user_id', $request->user()->id)
            ->where('course_lesson_id', $lesson->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Lección marcada como pendiente.';
        } else {
            LessonCompletion::create([
                'user_id' => $request->user()->id,
                'course_lesson_id' => $lesson->id,
                'course_id' => $course->id,
                'completed_at' => now(),
            ]);

            $message = 'Lección completada.';
        }

        $total = $course->lessons()->where('is_published', true)->count();
        $completed = LessonCompletion::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->count();
        $progress = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        $course->enrollments()
            ->where('user_id', $request->user()->id)
            ->update(['progress' => $progress]);

        if ($progress === 100) {
            $course->enrollments()
                ->where('user_id', $request->user()->id)
                ->update(['status' => 'completed', 'completed_at' => now()]);
        }

        return back()->with('message', $message);
    }
}
