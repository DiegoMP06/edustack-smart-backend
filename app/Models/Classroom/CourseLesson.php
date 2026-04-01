<?php

namespace App\Models\Classroom;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[Fillable([
    'name',
    'slug',
    'summary',
    'content',
    'type',
    'video_url',
    'video_duration_seconds',
    'order',
    'estimated_minutes',
    'is_published',
    'is_preview',
    'course_section_id',
    'course_id',
])]
class CourseLesson extends Model
{
    use HasSlug, LogsActivity, SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
            'is_preview' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
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

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function resources()
    {
        return $this->morphMany(CourseResource::class, 'resourceable')->orderBy('order');
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function discussions()
    {
        return $this->hasMany(CourseDiscussion::class);
    }
}
