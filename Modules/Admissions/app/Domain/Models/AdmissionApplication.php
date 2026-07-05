<?php

namespace Modules\Admissions\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class AdmissionApplication extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'admission_applications';

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'workflow_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'program',
        'source',
        'status',
        'notes',
        'submitted_at',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
}
