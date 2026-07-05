<?php

namespace Modules\Monitoring\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class MonitoringServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Monitoring';

    protected string $nameLower = 'monitoring';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        MonitoringBindingsServiceProvider::class,
    ];
}
