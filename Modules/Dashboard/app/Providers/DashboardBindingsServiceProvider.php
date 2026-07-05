<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Dashboard\Application\Contracts\DashboardRepositoryInterface;
use Modules\Dashboard\Application\Contracts\DashboardServiceInterface;
use Modules\Dashboard\Application\Services\DashboardService;
use Modules\Dashboard\Infrastructure\Repositories\DashboardRepository;

class DashboardBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
    }
}
