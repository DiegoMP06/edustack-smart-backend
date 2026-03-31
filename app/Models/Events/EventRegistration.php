<?php

namespace App\Models\Events;

use App\Enums\Events\EventRegistrationStatus;
use App\Models\Payments\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['event_id', 'user_id', 'status', 'payment_id', 'confirmed_at'])]
class EventRegistration extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'status' => EventRegistrationStatus::class,
            'confirmed_at' => 'datetime',
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
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function payable()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
}
