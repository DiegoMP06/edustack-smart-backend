<?php

namespace App\Models\Events;

use App\Enums\Events\RoundStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable([
    'name',
    'content',
    'round_number',
    'participants_per_round',
    'starting_from_scratch',
    'qualified_participants',
    'winners_count',
    'is_the_final',
    'status',
    'started_at',
    'ended_at',
    'event_activity_id',
])]
class CompetitionRound extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'round_number' => 'integer',
            'participants_per_round' => 'integer',
            'is_the_final' => 'boolean',
            'starting_from_scratch' => 'boolean',
            'qualified_participants' => 'integer',
            'winners_count' => 'integer',
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
