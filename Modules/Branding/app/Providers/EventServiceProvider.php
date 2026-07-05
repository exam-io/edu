<?php

namespace Modules\Branding\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Branding\Domain\Events\BrandingUpdated;
use Modules\Branding\Listeners\InvalidateBrandingCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BrandingUpdated::class => [
            InvalidateBrandingCache::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
