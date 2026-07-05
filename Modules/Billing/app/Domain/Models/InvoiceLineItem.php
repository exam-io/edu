<?php

namespace Modules\Billing\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Shared\Domain\Models\TenantAwareModel;

class InvoiceLineItem extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'invoice_line_items';

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'description',
        'quantity',
        'unit_amount',
        'tax_amount',
        'total_amount',
        'usage_key',
        'usage_count',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'unit_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'meta' => 'array',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
