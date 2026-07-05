<?php

namespace Modules\SaaS\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class SaaSServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'SaaS';

    protected string $nameLower = 'saas';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        SaaSBindingsServiceProvider::class,
    ];
}
