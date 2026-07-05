<?php

namespace Modules\Subscription\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class SubscriptionServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Subscription';

    protected string $nameLower = 'subscription';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        SubscriptionBindingsServiceProvider::class,
    ];
}
