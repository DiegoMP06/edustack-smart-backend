<?php

namespace App\Models\Classroom;

use App\Enums\Classroom\CourseEnrollmentStatus;
use App\Models\Payments\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'course_id',
    'status',
    'progress',
    'final_grade',
    'payment_id',
    'enrolled_at',
    'completed_at',
    'dropped_at',
])]
class CourseEnrollment extends Model
{
    protected function casts(): array
    {
        return [
            'status' => CourseEnrollmentStatus::class,
            'final_grade' => 'float',
            'enrolled_at' => 'datetime:Y-m-d H:i:s',
            'completed_at' => 'datetime:Y-m-d H:i:s',
            'dropped_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
