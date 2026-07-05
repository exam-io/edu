<?php

namespace Modules\WhiteLabel\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class WhiteLabelServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'WhiteLabel';

    protected string $nameLower = 'white-label';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        WhiteLabelBindingsServiceProvider::class,
    ];
}
