<?php

namespace Modules\Subscription\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Subscription\Domain\Events\SubscriptionRenewalRequested;
use Modules\Subscription\Listeners\QueueSubscriptionRenewal;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscriptionRenewalRequested::class => [
            QueueSubscriptionRenewal::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
