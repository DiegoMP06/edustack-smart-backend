<?php

namespace App\Models\Payments;

use App\Enums\Payments\PaymentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference_code',
    'qr_payload',
    'payable_type',
    'payable_id',
    'amount',
    'discount',
    'total',
    'currency',
    'status',
    'expires_at',
    'validated_by',
    'validated_at',
    'managed_by',
    'management_notes',
    'user_id',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'amount' => 'float',
            'discount' => 'float',
            'total' => 'float',
            'expires_at' => 'datetime',
            'validated_at' => 'datetime',
        ];
    }

    public function payable()
    {
        return $this->morphTo();
    }
}
