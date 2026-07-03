<?php

namespace Modules\Tenant\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Infrastructure\Repositories\EloquentTenantRepository;

class TenantBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantRepositoryInterface::class, EloquentTenantRepository::class);
    }
}
