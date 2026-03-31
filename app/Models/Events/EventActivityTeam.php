<?php

namespace App\Models\Events;

use App\Enums\Events\TeamStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['name', 'description', 'captain_user_id', 'status', 'event_activity_id'])]
class EventActivityTeam extends Model
{
    use LogsActivity, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => TeamStatus::class,
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

    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_user_id');
    }

    public function members()
    {
        return $this->hasMany(EventActivityTeamMember::class, 'event_activity_team_id');
    }
}
