<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case AWAITING_PAYMENT = 'awaiting_payment';
    case PAYMENT_UNDER_REVIEW = 'payment_under_review';
    case PAYMENT_VERIFIED = 'payment_verified';
    case PROCESSING = 'processing';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::AWAITING_PAYMENT => 'Awaiting Payment',
            self::PAYMENT_UNDER_REVIEW => 'Payment Under Review',
            self::PAYMENT_VERIFIED => 'Payment Verified',
            self::PROCESSING => 'Processing',
            self::OUT_FOR_DELIVERY => 'Out for Delivery',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
