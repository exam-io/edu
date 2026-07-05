<?php

namespace Modules\System\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSecurityPolicy extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'force_mfa',
        'session_ttl_minutes',
        'password_rotation_days',
        'allow_ip_restriction',
        'allowed_ip_ranges',
        'strict_transport_security',
        'meta',
    ];

    protected $casts = [
        'force_mfa' => 'bool',
        'allow_ip_restriction' => 'bool',
        'strict_transport_security' => 'bool',
        'allowed_ip_ranges' => 'array',
        'meta' => 'array',
    ];
}
