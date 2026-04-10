<?php

namespace App\Models\Classroom;

use App\Concerns\HasRelatables;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[Fillable([
    'name',
    'slug',
    'summary',
    'content',
    'code',
    'credits',
    'period',
    'is_published',
    'is_free',
    'price',
    'capacity',
    'start_date',
    'end_date',
    'enrollment_start_date',
    'enrollment_end_date',
    'course_status_id',
    'course_category_id',
    'user_id',
])]
class Course extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, LogsActivity, SoftDeletes, HasRelatables;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
            'is_free' => 'boolean',
            'price' => 'float',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'enrollment_start_date' => 'date:Y-m-d',
            'enrollment_end_date' => 'date:Y-m-d',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->useDisk('s3')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('cover')
            ->fit(Fit::Crop, 1920, 1080)
            ->quality(85)
            ->withResponsiveImages();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(CourseStatus::class, 'course_status_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)->orderBy('order');
    }

    public function resources()
    {
        return $this->morphMany(CourseResource::class, 'resourceable')->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function teachers()
    {
        return $this->hasMany(CourseTeacher::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function announcements()
    {
        return $this->hasMany(CourseAnnouncement::class);
    }

    public function discussions()
    {
        return $this->hasMany(CourseDiscussion::class);
    }
}
