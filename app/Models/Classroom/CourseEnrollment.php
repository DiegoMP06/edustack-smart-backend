<?php

namespace App\Models\Classroom;

use App\Enums\Classroom\CourseEnrollmentStatus;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected function casts(): array
    {
        return [
            'status' => CourseEnrollmentStatus::class,
            'final_grade' => 'float',
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
            'dropped_at' => 'datetime',
        ];
    }
}
