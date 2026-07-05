<?php

namespace Modules\System\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemHealthCheck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'check_name',
        'status',
        'checked_at',
        'meta',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'meta' => 'array',
    ];
}
