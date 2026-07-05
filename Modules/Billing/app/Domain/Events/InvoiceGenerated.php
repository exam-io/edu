<?php

namespace Modules\Billing\Domain\Events;

readonly class InvoiceGenerated
{
    public function __construct(
        public int $tenantId,
        public int $invoiceId,
    ) {}
}
