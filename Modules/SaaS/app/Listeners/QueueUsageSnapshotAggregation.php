<?php

namespace Modules\SaaS\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\SaaS\Domain\Events\UsageSnapshotRequested;
use Modules\SaaS\Jobs\AggregateUsageSnapshotJob;

class QueueUsageSnapshotAggregation implements ShouldQueue
{
    public function handle(UsageSnapshotRequested $event): void
    {
        AggregateUsageSnapshotJob::dispatch($event->tenantId, $event->snapshotDate);
    }
}
