<?php

namespace Modules\MobileProvisioning\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class MobileProvisioningServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'MobileProvisioning';

    protected string $nameLower = 'mobileprovisioning';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        MobileProvisioningBindingsServiceProvider::class,
    ];
}
