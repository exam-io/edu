<?php

namespace Modules\WhiteLabel\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\WhiteLabel\Application\Contracts\DomainServiceInterface;
use Modules\WhiteLabel\Application\Contracts\NavigationServiceInterface;
use Modules\WhiteLabel\Application\Services\DomainService;
use Modules\WhiteLabel\Application\Services\NavigationService;

class WhiteLabelBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DomainServiceInterface::class, DomainService::class);
        $this->app->bind(NavigationServiceInterface::class, NavigationService::class);
    }
}
