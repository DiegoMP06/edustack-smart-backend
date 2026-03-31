<?php

namespace App\Enums\Payments;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case VALIDATING = 'validating';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';
    case REFUNDED = 'refunded';
    case WAIVED = 'waived';
}
