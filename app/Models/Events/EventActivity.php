<?php

namespace App\Models\Events;

use App\Enums\Events\BehaviorType;
use App\Models\Classroom\Course;
use App\Models\Forms\FormAttachment;
use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    'description',
    'content',
    'requirements',
    'is_online',
    'online_link',
    'location',
    'lat',
    'lng',
    'has_teams',
    'requires_team',
    'min_team_size',
    'max_team_size',
    'max_participants',
    'only_students',
    'is_competition',
    'price',
    'speakers',
    'repository_url',
    'is_published',
    'started_at',
    'ended_at',
    'registration_started_at',
    'registration_ended_at',
    'course_id',
    'project_id',
    'difficulty_level_id',
    'event_status_id',
    'event_activity_type_id',
    'event_id',
])]
class EventActivity extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, LogsActivity, Searchable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'speakers' => 'array',
            'is_online' => 'boolean',
            'has_teams' => 'boolean',
            'requires_team' => 'boolean',
            'only_students' => 'boolean',
            'is_competition' => 'boolean',
            'is_published' => 'boolean',
            'price' => 'float',
            'lat' => 'float',
            'lng' => 'float',
            'started_at' => 'datetime:Y-m-d H:i:s',
            'ended_at' => 'datetime:Y-m-d H:i:s',
            'registration_started_at' => 'datetime:Y-m-d H:i:s',
            'registration_ended_at' => 'datetime:Y-m-d H:i:s',
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
        $this->addMediaCollection('gallery')
            ->useDisk('s3');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('hero')
            ->fit(Fit::Crop, 1920, 1080)
            ->quality(85)
            ->sharpen(10);

        $this->addMediaConversion('main')
            ->fit(Fit::Crop, 1200, 620)
            ->quality(85)
            ->sharpen(10)
            ->withResponsiveImages();
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'event' => $this->event->name,
            'type' => $this->type?->name,
            'status' => $this->status->name,
        ];
    }

    public function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['event', 'type', 'status']);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeCompetitions($query)
    {
        return $query->where('is_competition', true);
    }

    public function scopeByBehavior($query, string $type)
    {
        $value = BehaviorType::tryFrom($type)?->value ?? $type;

        return $query->join('event_activity_types', 'event_activity_types.id', '=', 'event_activities.event_activity_type_id')
            ->where('event_activity_types.behavior_type', $value)
            ->select('event_activities.*');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function type()
    {
        return $this->belongsTo(EventActivityType::class, 'event_activity_type_id');
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class, 'event_status_id');
    }

    public function difficultyLevel()
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_level_id');
    }

    public function difficulty()
    {
        return $this->difficultyLevel();
    }

    public function categories()
    {
        return $this->belongsToMany(EventActivityCategory::class, 'event_activity_category');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function teams()
    {
        return $this->hasMany(EventActivityTeam::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventActivityRegistration::class);
    }

    public function rounds()
    {
        return $this->hasMany(CompetitionRound::class);
    }

    public function formAttachments()
    {
        return $this->morphMany(FormAttachment::class, 'formable');
    }
}
