<?php

namespace App\Models\Classroom;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'course_lesson_id', 'course_id', 'completed_at'])]
class LessonCompletion extends Model
{
    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
