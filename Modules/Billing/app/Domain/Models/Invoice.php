<?php

namespace Modules\Billing\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Shared\Domain\Models\TenantAwareModel;

class Invoice extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'tenant_id',
        'number',
        'status',
        'currency',
        'subtotal_amount',
        'tax_amount',
        'total_amount',
        'period_start',
        'period_end',
        'issued_at',
        'due_at',
        'paid_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(InvoiceLineItem::class);
    }
}
