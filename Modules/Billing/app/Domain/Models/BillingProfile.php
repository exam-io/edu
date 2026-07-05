<?php

namespace Modules\Billing\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class BillingProfile extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'billing_profiles';

    protected $fillable = [
        'tenant_id',
        'legal_name',
        'email',
        'phone',
        'country',
        'city',
        'address_line',
        'postal_code',
        'tax_id',
        'currency',
    ];
}
