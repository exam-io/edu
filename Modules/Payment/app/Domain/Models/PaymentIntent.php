<?php

namespace Modules\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentIntent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'provider',
        'provider_intent_id',
        'currency',
        'amount',
        'status',
        'client_secret',
        'meta',
    ];

    protected $casts = [
        'amount' => 'float',
        'meta' => 'array',
    ];
}
