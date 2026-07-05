<?php

namespace Modules\Billing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Billing\Domain\Events\InvoiceGenerated;
use Modules\Billing\Domain\Models\Invoice;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly int $invoiceId,
    ) {}

    public function handle(): void
    {
        $invoice = Invoice::query()->where('tenant_id', $this->tenantId)->find($this->invoiceId);
        if (! $invoice instanceof Invoice) {
            return;
        }

        $invoice->update([
            'status' => 'issued',
            'issued_at' => now(),
            'due_at' => $invoice->due_at ?? now()->addDays(15),
        ]);

        event(new InvoiceGenerated($this->tenantId, $this->invoiceId));
    }
}
