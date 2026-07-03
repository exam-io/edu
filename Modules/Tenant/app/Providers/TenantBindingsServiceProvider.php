<?php

namespace Modules\Tenant\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Application\Services\TenantBrandingService;
use Modules\Tenant\Application\Services\TenantConfigurationService;
use Modules\Tenant\Application\Services\TenantContextService;
use Modules\Tenant\Application\Services\TenantProvisioningService;
use Modules\Tenant\Application\Services\TenantResolverService;
use Modules\Tenant\Domain\Events\TenantResolved;
use Modules\Tenant\Infrastructure\Repositories\EloquentTenantRepository;
use Modules\Tenant\Listeners\LogTenantResolved;

class TenantBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantRepositoryInterface::class, EloquentTenantRepository::class);
        $this->app->singleton(TenantResolverService::class);
        $this->app->singleton(TenantContextService::class);
        $this->app->singleton(TenantBrandingService::class);
        $this->app->singleton(TenantConfigurationService::class);
        $this->app->singleton(TenantProvisioningService::class);
    }

    public function boot(): void
    {
        Event::listen(TenantResolved::class, LogTenantResolved::class);
    }
}
