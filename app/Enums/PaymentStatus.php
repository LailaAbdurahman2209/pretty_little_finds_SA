<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case AWAITING_PAYMENT = 'awaiting_payment';
    case PROOF_UPLOADED = 'proof_uploaded';
    case UNDER_REVIEW = 'under_review';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::AWAITING_PAYMENT => 'Awaiting Payment',
            self::PROOF_UPLOADED => 'Proof Uploaded',
            self::UNDER_REVIEW => 'Payment Under Review',
            self::VERIFIED => 'Payment Verified',
            self::REJECTED => 'Payment Rejected',
        };
    }
}
