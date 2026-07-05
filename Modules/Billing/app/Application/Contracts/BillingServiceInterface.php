<?php

namespace Modules\Billing\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Billing\Application\DTOs\InvoiceGenerationData;
use Modules\Billing\Application\DTOs\InvoiceQueryData;
use Modules\Billing\Application\DTOs\UpsertBillingProfileData;
use Modules\Billing\Domain\Models\BillingProfile;
use Modules\Billing\Domain\Models\Invoice;

interface BillingServiceInterface
{
    public function center(int $tenantId): array;

    public function invoices(int $tenantId, InvoiceQueryData $query): LengthAwarePaginator;

    public function showInvoice(int $tenantId, int $invoiceId): Invoice;

    public function requestInvoiceGeneration(int $tenantId, InvoiceGenerationData $data): Invoice;

    public function upsertProfile(int $tenantId, UpsertBillingProfileData $data): BillingProfile;
}
