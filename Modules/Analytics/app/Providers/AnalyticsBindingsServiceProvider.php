<?php

namespace Modules\Analytics\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Analytics\Application\Contracts\AnalyticsEngineInterface;
use Modules\Analytics\Application\Contracts\AnalyticsRepositoryInterface;
use Modules\Analytics\Application\Contracts\AnalyticsServiceInterface;
use Modules\Analytics\Application\Services\AnalyticsEngine;
use Modules\Analytics\Application\Services\AnalyticsService;
use Modules\Analytics\Infrastructure\Repositories\AnalyticsRepository;

class AnalyticsBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AnalyticsRepositoryInterface::class, AnalyticsRepository::class);
        $this->app->bind(AnalyticsEngineInterface::class, AnalyticsEngine::class);
        $this->app->bind(AnalyticsServiceInterface::class, AnalyticsService::class);
    }
}
