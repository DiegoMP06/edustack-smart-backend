<?php

namespace App\Models\Events;

use App\Enums\Events\ActivityRegistrationStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['user_id', 'event_activity_id', 'status', 'confirmed_at'])]
class EventActivityRegistration extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'status' => ActivityRegistrationStatus::class,
            'confirmed_at' => 'datetime',
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
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(EventActivity::class, 'event_activity_id');
    }
}
