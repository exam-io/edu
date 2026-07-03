<?php

namespace Modules\Parent\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class ParentServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Parent';

    protected string $nameLower = 'parent';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        ParentBindingsServiceProvider::class,
    ];
}
