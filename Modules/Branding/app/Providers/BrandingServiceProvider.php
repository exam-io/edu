<?php

namespace Modules\Branding\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class BrandingServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Branding';

    protected string $nameLower = 'branding';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        BrandingBindingsServiceProvider::class,
    ];
}
