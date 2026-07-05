<?php

namespace Modules\Operations\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class OperationsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Operations';

    protected string $nameLower = 'operations';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        OperationsBindingsServiceProvider::class,
    ];
}
