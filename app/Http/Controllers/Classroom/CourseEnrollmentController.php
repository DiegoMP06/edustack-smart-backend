<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseEnrollment;
use App\Models\Payments\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseEnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        if (
            ! $course->enrollment_start_date
            || ! $course->enrollment_end_date
            || now()->lt($course->enrollment_start_date)
            || now()->gt($course->enrollment_end_date)
        ) {
            throw ValidationException::withMessages([
                'enrollment' => 'Las inscripciones a este curso no están abiertas.',
            ]);
        }

        if ($course->enrollments()->where('user_id', $request->user()->id)->exists()) {
            throw ValidationException::withMessages([
                'enrollment' => 'Ya estás inscrito a este curso.',
            ]);
        }

        if ($course->capacity !== null) {
            $activeEnrollments = $course->enrollments()
                ->whereIn('status', ['active', 'completed'])
                ->count();

            if ($activeEnrollments >= $course->capacity) {
                throw ValidationException::withMessages([
                    'enrollment' => 'Este curso ha alcanzado su capacidad máxima.',
                ]);
            }
        }

        $enrollment = $course->enrollments()->create([
            'user_id' => $request->user()->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        if (! $course->is_free && $course->price > 0) {
            $year = now()->year;
            $count = Payment::whereYear('created_at', $year)->count() + 1;
            $ref = 'PAY-'.$year.'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);

            $enrollment->payable()->create([
                'reference_code' => $ref,
                'qr_payload' => encrypt($ref),
                'amount' => $course->price,
                'discount' => 0,
                'total' => $course->price,
                'user_id' => $request->user()->id,
                'status' => 'pending',
            ]);
        }

        return back()->with('message', 'Inscripción realizada correctamente.');
    }

    public function destroy(Request $request, Course $course, CourseEnrollment $enrollment)
    {
        abort_if($enrollment->course_id !== $course->id, 404);

        if ($enrollment->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403);
        }

        $enrollment->update([
            'status' => 'dropped',
            'dropped_at' => now(),
        ]);

        if ($enrollment->payment && ($enrollment->payment->status->value ?? $enrollment->payment->status) === 'pending') {
            $enrollment->payment->update(['status' => 'expired']);
        }

        return back()->with('message', 'Inscripción cancelada.');
    }
}
