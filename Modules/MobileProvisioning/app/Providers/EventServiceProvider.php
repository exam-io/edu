<?php

namespace Modules\MobileProvisioning\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\MobileProvisioning\Domain\Events\MobileConfigPublished;
use Modules\MobileProvisioning\Listeners\InvalidateMobileConfigCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MobileConfigPublished::class => [InvalidateMobileConfigCache::class],
    ];

    protected static $shouldDiscoverEvents = false;
}
