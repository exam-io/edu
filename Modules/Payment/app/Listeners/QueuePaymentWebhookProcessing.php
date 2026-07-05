<?php

namespace Modules\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Payment\Domain\Events\PaymentWebhookReceived;
use Modules\Payment\Jobs\ProcessPaymentWebhookJob;

class QueuePaymentWebhookProcessing implements ShouldQueue
{
    public function handle(PaymentWebhookReceived $event): void
    {
        ProcessPaymentWebhookJob::dispatch($event->tenantId, $event->webhookLogId);
    }
}
