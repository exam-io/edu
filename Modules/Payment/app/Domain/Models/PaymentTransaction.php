<?php

namespace Modules\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'payment_intent_id',
        'provider',
        'provider_transaction_id',
        'status',
        'amount',
        'currency',
        'processed_at',
        'meta',
    ];

    protected $casts = [
        'amount' => 'float',
        'processed_at' => 'datetime',
        'meta' => 'array',
    ];
}
