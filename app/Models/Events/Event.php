<?php

namespace App\Models\Events;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
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
    'price',
    'percent_off',
    'capacity',
    'is_online',
    'online_link',
    'location',
    'lat',
    'lng',
    'registration_started_at',
    'registration_ended_at',
    'start_date',
    'end_date',
    'is_published',
    'event_status_id',
    'user_id',
])]
class Event extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, LogsActivity, Searchable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'price' => 'float',
            'percent_off' => 'float',
            'is_online' => 'boolean',
            'lat' => 'float',
            'lng' => 'float',
            'registration_started_at' => 'datetime:Y-m-d H:i:s',
            'registration_ended_at' => 'datetime:Y-m-d H:i:s',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'is_published' => 'boolean',
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
        $this->addMediaCollection('logo')
            ->useDisk('s3')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 1080, 1080)
            ->quality(85)
            ->sharpen(10)
            ->withResponsiveImages();
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'summary' => $this->summary,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'is_online' => $this->is_online,
            'status' => $this->status->name,
            'author' => $this->author()->get(['name', 'father_last_name', 'email'])->toArray(),
        ];
    }

    public function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['status', 'author']);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(EventActivity::class);
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class, 'event_status_id');
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'event_collaborators')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
