<?php

namespace Modules\Tenant\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Tenant\Domain\Enums\TenantStatus;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'custom_domain',
        'status',
        'plan',
        'provisioned_at',
        'suspended_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TenantStatus::class,
            'provisioned_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    public function settings(): HasOne
    {
        return $this->hasOne(TenantSetting::class);
    }

    public function isActive(): bool
    {
        return $this->status === TenantStatus::ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === TenantStatus::SUSPENDED;
    }
}
