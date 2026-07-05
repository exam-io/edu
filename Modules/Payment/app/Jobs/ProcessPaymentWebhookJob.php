<?php

namespace Modules\Payment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Domain\Models\PaymentWebhookLog;

class ProcessPaymentWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly int $webhookLogId,
    ) {}

    public function handle(): void
    {
        $log = PaymentWebhookLog::query()
            ->where('tenant_id', $this->tenantId)
            ->find($this->webhookLogId);

        if (! $log instanceof PaymentWebhookLog) {
            return;
        }

        $log->update([
            'status' => 'processed',
            'processed_at' => now(),
            'error_message' => null,
        ]);
    }
}
