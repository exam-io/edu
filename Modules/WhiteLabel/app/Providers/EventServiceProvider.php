<?php

namespace Modules\WhiteLabel\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\WhiteLabel\Domain\Events\DomainMapped;
use Modules\WhiteLabel\Domain\Events\NavigationConfigurationUpdated;
use Modules\WhiteLabel\Listeners\InvalidateWhiteLabelCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DomainMapped::class => [InvalidateWhiteLabelCache::class],
        NavigationConfigurationUpdated::class => [InvalidateWhiteLabelCache::class],
    ];

    protected static $shouldDiscoverEvents = false;
}
