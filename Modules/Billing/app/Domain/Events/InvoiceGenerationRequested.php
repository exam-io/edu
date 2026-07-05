<?php

namespace Modules\Billing\Domain\Events;

readonly class InvoiceGenerationRequested
{
    public function __construct(
        public int $tenantId,
        public int $invoiceId,
    ) {}
}
