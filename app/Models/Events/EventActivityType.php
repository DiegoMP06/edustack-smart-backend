<?php

namespace App\Models\Events;

use App\Enums\Events\BehaviorType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[Fillable(['name', 'slug', 'description', 'icon', 'behavior_type', 'order'])]
class EventActivityType extends Model
{
    use HasSlug, LogsActivity;

    protected function casts(): array
    {
        return [
            'behavior_type' => BehaviorType::class,
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

    public function activities()
    {
        return $this->hasMany(EventActivity::class, 'event_activity_type_id');
    }
}
