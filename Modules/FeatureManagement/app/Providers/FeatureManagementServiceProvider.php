<?php

namespace Modules\FeatureManagement\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class FeatureManagementServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'FeatureManagement';

    protected string $nameLower = 'featuremanagement';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        FeatureManagementBindingsServiceProvider::class,
    ];
}
