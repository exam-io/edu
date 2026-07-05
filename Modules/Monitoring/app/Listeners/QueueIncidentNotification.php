<?php

namespace Modules\Monitoring\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Monitoring\Domain\Events\MetricThresholdBreached;

class QueueIncidentNotification implements ShouldQueue
{
    public function handle(MetricThresholdBreached $event): void
    {
        Log::warning('Monitoring threshold breached.', [
            'tenant_id' => $event->tenantId,
            'metric_key' => $event->metricKey,
            'severity' => $event->severity,
            'meta' => $event->meta,
        ]);
    }
}
