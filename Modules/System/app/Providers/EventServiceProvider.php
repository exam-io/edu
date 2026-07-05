<?php

namespace Modules\System\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\System\Domain\Events\SystemHealthCheckFailed;
use Modules\System\Listeners\QueueFailedHealthAlert;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SystemHealthCheckFailed::class => [
            QueueFailedHealthAlert::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
