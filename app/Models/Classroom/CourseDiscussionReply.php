<?php

namespace App\Models\Classroom;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable([
    'content',
    'is_solution',
    'parent_id',
    'course_discussion_id',
    'user_id',
])]
class CourseDiscussionReply extends Model
{
    use LogsActivity, SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_solution' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function discussion()
    {
        return $this->belongsTo(CourseDiscussion::class, 'course_discussion_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
