<?php

namespace Modules\FeatureManagement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\FeatureManagement\Domain\Events\FeatureFlagsChanged;
use Modules\FeatureManagement\Listeners\InvalidateFeatureFlagCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        FeatureFlagsChanged::class => [InvalidateFeatureFlagCache::class],
    ];

    protected static $shouldDiscoverEvents = false;
}
