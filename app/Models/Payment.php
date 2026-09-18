<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_method', 'amount', 
        'proof_of_payment_path', 'status', 'verified_at', 'notes'
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
