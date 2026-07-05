<?php

namespace Modules\CRM\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class Lead extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_leads';

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'status',
        'interest',
        'notes',
        'assigned_to',
        'converted_at',
    ];

    protected function casts(): array
    {
        return [
            'converted_at' => 'datetime',
        ];
    }
}
