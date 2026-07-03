<?php

namespace Modules\Institute\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Institute\Domain\Enums\InstituteStatus;
use Modules\Tenant\Domain\Models\Tenant;

class Institute extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'institutes';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'code',
        'status',
        'email',
        'phone',
        'website',
        'description',
        'address',
        'branding',
        'configuration',
        'onboarding_step',
        'onboarded_at',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => InstituteStatus::class,
            'address' => 'array',
            'branding' => 'array',
            'configuration' => 'array',
            'onboarded_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function academicSessions(): HasMany
    {
        return $this->hasMany(AcademicSession::class);
    }

    public function currentAcademicSession(): HasOne
    {
        return $this->hasOne(AcademicSession::class)->where('is_current', true);
    }

    public function isProvisioning(): bool
    {
        return $this->status === InstituteStatus::Provisioning;
    }
}
