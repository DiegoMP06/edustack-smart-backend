<?php

namespace App\Models\Events;

use App\Enums\Events\TeamMemberRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['user_id', 'event_activity_team_id', 'role', 'joined_at'])]
class EventActivityTeamMember extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'role' => TeamMemberRole::class,
            'joined_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(EventActivityTeam::class, 'event_activity_team_id');
    }
}
