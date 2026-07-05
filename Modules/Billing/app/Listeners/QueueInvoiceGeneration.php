<?php

namespace Modules\Billing\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Billing\Domain\Events\InvoiceGenerationRequested;
use Modules\Billing\Jobs\GenerateInvoiceJob;

class QueueInvoiceGeneration implements ShouldQueue
{
    public function handle(InvoiceGenerationRequested $event): void
    {
        GenerateInvoiceJob::dispatch($event->tenantId, $event->invoiceId);
    }
}
