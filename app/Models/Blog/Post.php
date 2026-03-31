<?php

namespace App\Models\Blog;

use App\Models\User;
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
    'summary',
    'content',
    'views_count',
    'reading_time_minutes',
    'is_featured',
    'is_published',
    'published_at',
    'post_type_id',
    'user_id',
])]
class Post extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, LogsActivity, Searchable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'reading_time_minutes' => 'integer',
            'views_count' => 'integer',
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

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'summary' => $this->summary,
            'published_at' => $this->published_at,
            'categories' => $this->categories()->pluck('post_categories.name')->toArray(),
            'type' => $this->type()->pluck('name')->toArray(),
            'author' => $this->author()->get(['name', 'father_last_name', 'email'])->toArray(),
        ];
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['categories', 'type', 'media', 'author']);
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

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(PostType::class, 'post_type_id');
    }

    public function categories()
    {
        return $this->belongsToMany(PostCategory::class, 'post_category', 'post_id', 'post_category_id')->withPivot('id');
    }
}
