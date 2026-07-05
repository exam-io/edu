<?php

namespace Modules\System\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class SystemServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'System';

    protected string $nameLower = 'system';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        SystemBindingsServiceProvider::class,
    ];
}
