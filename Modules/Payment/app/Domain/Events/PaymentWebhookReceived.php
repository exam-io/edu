<?php

namespace Modules\Payment\Domain\Events;

readonly class PaymentWebhookReceived
{
    public function __construct(
        public int $tenantId,
        public int $webhookLogId,
    ) {}
}
