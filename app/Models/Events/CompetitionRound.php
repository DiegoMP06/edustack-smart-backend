<?php

namespace App\Models\Events;

use App\Enums\Events\RoundStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'name',
    'description',
    'content',
    'round_number',
    'participants_per_round',
    'starting_from_scratch',
    'qualified_participants',
    'winners_count',
    'is_the_final',
    'rate_by_part',
    'status',
    'started_at',
    'ended_at',
    'event_activity_id',
])]

class CompetitionRound extends Model implements HasMedia
{
    use LogsActivity, HasFactory, InteractsWithMedia, Searchable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'round_number' => 'integer',
            'participants_per_round' => 'integer',
            'starting_from_scratch' => 'boolean',
            'qualified_participants' => 'integer',
            'winners_count' => 'integer',
            'is_the_final' => 'boolean',
            'rate_by_part' => 'boolean',
            'status' => RoundStatus::class,
            'started_at' => 'datetime:Y-m-d H:i:s',
            'ended_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function activity()
    {
        return $this->belongsTo(EventActivity::class, 'event_activity_id');
    }

    public function exercises()
    {
        return $this->hasMany(CompetitionRoundExercise::class, 'competition_round_id');
    }
}
