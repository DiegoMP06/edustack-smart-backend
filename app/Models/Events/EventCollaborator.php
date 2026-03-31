<?php

namespace App\Models\Events;

use App\Enums\Events\EventCollaboratorRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['event_id', 'user_id', 'role'])]
class EventCollaborator extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'role' => EventCollaboratorRole::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
