<?php

namespace Modules\System\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\System\Application\Contracts\SystemHealthServiceInterface;
use Modules\System\Application\Contracts\SystemSecurityServiceInterface;
use Modules\System\Application\Services\SystemHealthService;
use Modules\System\Application\Services\SystemSecurityService;

class SystemBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SystemSecurityServiceInterface::class, SystemSecurityService::class);
        $this->app->bind(SystemHealthServiceInterface::class, SystemHealthService::class);
    }
}
