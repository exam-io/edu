<?php

namespace Modules\Monitoring\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertIncident extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'metric_key',
        'severity',
        'status',
        'triggered_at',
        'resolved_at',
        'meta',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
        'meta' => 'array',
    ];
}
