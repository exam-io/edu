<?php

namespace Modules\Subscription\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'code',
        'name',
        'description',
        'billing_interval',
        'price_amount',
        'currency',
        'quota',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'price_amount' => 'float',
        'quota' => 'array',
        'is_active' => 'bool',
        'meta' => 'array',
    ];
}
