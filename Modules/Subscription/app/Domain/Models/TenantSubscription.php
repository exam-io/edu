<?php

namespace Modules\Subscription\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantSubscription extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'starts_at',
        'renews_at',
        'ends_at',
        'canceled_at',
        'meta',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'renews_at' => 'datetime',
        'ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'meta' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
