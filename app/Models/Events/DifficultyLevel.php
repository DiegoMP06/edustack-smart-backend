<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[Fillable(['name', 'slug', 'color', 'description', 'order'])]
class DifficultyLevel extends Model
{
    use HasSlug, LogsActivity;

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
        return $this->hasMany(EventActivity::class);
    }

    public function exercises()
    {
        return $this->hasMany(CompetitionRoundExercise::class);
    }
}
