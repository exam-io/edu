<?php

namespace Modules\Analytics\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Analytics\Domain\Events\AnalyticsEventTracked;
use Modules\Analytics\Jobs\AggregateTenantMetricsJob;

class QueueMetricAggregation implements ShouldQueue
{
    public function handle(AnalyticsEventTracked $event): void
    {
        AggregateTenantMetricsJob::dispatch($event->tenantId);
    }
}
