<?php

namespace Modules\SaaS\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\SaaS\Domain\Events\UsageSnapshotRequested;
use Modules\SaaS\Listeners\QueueUsageSnapshotAggregation;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UsageSnapshotRequested::class => [
            QueueUsageSnapshotAggregation::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
