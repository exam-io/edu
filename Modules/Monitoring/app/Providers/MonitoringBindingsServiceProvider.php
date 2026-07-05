<?php

namespace Modules\Monitoring\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Monitoring\Application\Contracts\MetricsServiceInterface;
use Modules\Monitoring\Application\Services\MetricsService;

class MonitoringBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MetricsServiceInterface::class, MetricsService::class);
    }
}
