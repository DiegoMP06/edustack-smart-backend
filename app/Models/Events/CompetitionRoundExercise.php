<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable([
    'name',
    'content',
    'score',
    'order',
    'time_limit_seconds',
    'difficulty_level_id',
    'competition_round_id',
])]
class CompetitionRoundExercise extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'score' => 'float',
            'order' => 'integer',
            'time_limit_seconds' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function round()
    {
        return $this->belongsTo(CompetitionRound::class, 'competition_round_id');
    }

    public function difficulty()
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_level_id');
    }
}
