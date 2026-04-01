<?php

namespace App\Models\Classroom;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable([
    'name',
    'instructions',
    'summary',
    'max_score',
    'passing_score',
    'allow_late_submissions',
    'max_attempts',
    'submission_type',
    'is_published',
    'due_date',
    'available_from',
    'course_id',
    'course_lesson_id',
    'user_id',
])]
class Assignment extends Model
{
    use LogsActivity, SoftDeletes;

    protected function casts(): array
    {
        return [
            'instructions' => 'array',
            'allow_late_submissions' => 'boolean',
            'is_published' => 'boolean',
            'max_score' => 'float',
            'passing_score' => 'float',
            'due_date' => 'datetime:Y-m-d H:i:s',
            'available_from' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
