<?php

namespace App\Providers;

use App\Support\Tenancy\Contracts\CacheIsolationInterface;
use App\Support\Tenancy\Contracts\QueueIsolationInterface;
use App\Support\Tenancy\Contracts\StorageIsolationInterface;
use App\Support\Tenancy\Contracts\TenantContextInterface;
use App\Support\Tenancy\Contracts\TenantResolverInterface;
use App\Support\Tenancy\Isolation\TenantCacheIsolation;
use App\Support\Tenancy\Isolation\TenantQueueIsolation;
use App\Support\Tenancy\Isolation\TenantStorageIsolation;
use App\Support\Tenancy\Resolvers\DomainTenantResolver;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\ServiceProvider;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContextInterface::class, TenantContext::class);
        $this->app->bind(TenantResolverInterface::class, DomainTenantResolver::class);

        $this->app->bind(StorageIsolationInterface::class, TenantStorageIsolation::class);
        $this->app->bind(CacheIsolationInterface::class, TenantCacheIsolation::class);
        $this->app->bind(QueueIsolationInterface::class, TenantQueueIsolation::class);
    }
}
