<?php

namespace Modules\Insights\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Analytics\Domain\Events\MetricsAggregated;
use Modules\Insights\Jobs\GenerateInsightsJob;

class QueueInsightGeneration implements ShouldQueue
{
    public function handle(MetricsAggregated $event): void
    {
        GenerateInsightsJob::dispatch($event->tenantId);
    }
}
