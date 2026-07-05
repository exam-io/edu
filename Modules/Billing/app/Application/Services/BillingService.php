<?php

namespace Modules\Billing\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\Billing\Application\Contracts\BillingServiceInterface;
use Modules\Billing\Application\DTOs\InvoiceGenerationData;
use Modules\Billing\Application\DTOs\InvoiceQueryData;
use Modules\Billing\Application\DTOs\UpsertBillingProfileData;
use Modules\Billing\Domain\Events\InvoiceGenerationRequested;
use Modules\Billing\Domain\Models\BillingProfile;
use Modules\Billing\Domain\Models\Invoice;
use Modules\Billing\Domain\Models\InvoiceLineItem;

class BillingService implements BillingServiceInterface
{
    public function center(int $tenantId): array
    {
        $invoice = Invoice::query()->where('tenant_id', $tenantId)->latest('id')->first();

        return [
            'latest_invoice' => $invoice,
            'outstanding_total' => (float) Invoice::query()->where('tenant_id', $tenantId)->whereIn('status', ['issued', 'overdue'])->sum('total_amount'),
            'mrr' => (float) Invoice::query()->where('tenant_id', $tenantId)->where('status', 'paid')->where('issued_at', '>=', now()->startOfMonth())->sum('total_amount'),
        ];
    }

    public function invoices(int $tenantId, InvoiceQueryData $query): LengthAwarePaginator
    {
        $builder = Invoice::query()->where('tenant_id', $tenantId)->latest('id');

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->search !== null && $query->search !== '') {
            $builder->where(fn ($q) => $q->where('number', 'like', '%' . $query->search . '%')->orWhere('currency', 'like', '%' . $query->search . '%'));
        }

        return $builder->paginate($query->perPage);
    }

    public function showInvoice(int $tenantId, int $invoiceId): Invoice
    {
        return Invoice::query()->where('tenant_id', $tenantId)->with('lineItems')->findOrFail($invoiceId);
    }

    public function requestInvoiceGeneration(int $tenantId, InvoiceGenerationData $data): Invoice
    {
        $subtotal = 0.0;
        $tax = 0.0;

        foreach ($data->lineItems as $item) {
            $subtotal += ((float) ($item['quantity'] ?? 1)) * ((float) ($item['unit_amount'] ?? 0));
            $tax += (float) ($item['tax_amount'] ?? 0);
        }

        $invoice = Invoice::query()->create([
            'tenant_id' => $tenantId,
            'number' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
            'status' => 'draft',
            'currency' => $data->currency,
            'subtotal_amount' => round($subtotal, 2),
            'tax_amount' => round($tax, 2),
            'total_amount' => round($subtotal + $tax, 2),
            'period_start' => $data->periodStart,
            'period_end' => $data->periodEnd,
            'due_at' => $data->dueAt,
            'meta' => [],
        ]);

        foreach ($data->lineItems as $lineItem) {
            $qty = (int) ($lineItem['quantity'] ?? 1);
            $unit = (float) ($lineItem['unit_amount'] ?? 0);
            $lineTax = (float) ($lineItem['tax_amount'] ?? 0);

            InvoiceLineItem::query()->create([
                'tenant_id' => $tenantId,
                'invoice_id' => $invoice->id,
                'description' => (string) ($lineItem['description'] ?? 'Usage line item'),
                'quantity' => $qty,
                'unit_amount' => $unit,
                'tax_amount' => $lineTax,
                'total_amount' => round(($qty * $unit) + $lineTax, 2),
                'usage_key' => isset($lineItem['usage_key']) ? (string) $lineItem['usage_key'] : null,
                'usage_count' => isset($lineItem['usage_count']) ? (int) $lineItem['usage_count'] : null,
                'meta' => is_array($lineItem['meta'] ?? null) ? $lineItem['meta'] : [],
            ]);
        }

        event(new InvoiceGenerationRequested($tenantId, $invoice->id));

        return $invoice->refresh();
    }

    public function upsertProfile(int $tenantId, UpsertBillingProfileData $data): BillingProfile
    {
        $profile = BillingProfile::query()->firstOrNew(['tenant_id' => $tenantId]);
        $profile->fill(array_filter([
            'legal_name' => $data->legalName,
            'email' => $data->email,
            'phone' => $data->phone,
            'country' => $data->country,
            'city' => $data->city,
            'address_line' => $data->addressLine,
            'postal_code' => $data->postalCode,
            'tax_id' => $data->taxId,
            'currency' => $data->currency,
        ], static fn ($v) => $v !== null));
        $profile->save();

        return $profile->refresh();
    }
}
