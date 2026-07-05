<?php

namespace Modules\MobileProvisioning\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\MobileProvisioning\Application\Contracts\MobileConfigServiceInterface;
use Modules\MobileProvisioning\Application\Services\MobileConfigService;

class MobileProvisioningBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MobileConfigServiceInterface::class, MobileConfigService::class);
    }
}
