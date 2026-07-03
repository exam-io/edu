<?php

namespace Modules\Parent\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Parent\Application\Contracts\ParentProvisioningServiceInterface;
use Modules\Parent\Application\Contracts\ParentServiceInterface;
use Modules\Parent\Application\Contracts\ParentTenantRepositoryInterface;
use Modules\Parent\Application\Services\ParentProvisioningService;
use Modules\Parent\Application\Services\ParentService;
use Modules\Parent\Infrastructure\Repositories\ParentTenantRepository;

class ParentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ParentTenantRepositoryInterface::class, ParentTenantRepository::class);
        $this->app->bind(ParentServiceInterface::class, ParentService::class);
        $this->app->bind(ParentProvisioningServiceInterface::class, ParentProvisioningService::class);
    }
}
