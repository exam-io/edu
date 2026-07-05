<?php

namespace Modules\SaaS\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsageCounter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'metric_key',
        'period_key',
        'counter',
        'meta',
    ];

    protected $casts = [
        'counter' => 'float',
        'meta' => 'array',
    ];
}
