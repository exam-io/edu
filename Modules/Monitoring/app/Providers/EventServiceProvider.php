<?php

namespace Modules\Monitoring\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Monitoring\Domain\Events\MetricThresholdBreached;
use Modules\Monitoring\Listeners\QueueIncidentNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MetricThresholdBreached::class => [
            QueueIncidentNotification::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
