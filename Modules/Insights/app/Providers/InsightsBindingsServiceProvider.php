<?php

namespace Modules\Insights\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Insights\Application\Contracts\InsightEngineInterface;
use Modules\Insights\Application\Contracts\InsightRepositoryInterface;
use Modules\Insights\Application\Contracts\InsightsServiceInterface;
use Modules\Insights\Application\Services\InsightEngine;
use Modules\Insights\Application\Services\InsightsService;
use Modules\Insights\Infrastructure\Repositories\InsightRepository;

class InsightsBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InsightRepositoryInterface::class, InsightRepository::class);
        $this->app->bind(InsightEngineInterface::class, InsightEngine::class);
        $this->app->bind(InsightsServiceInterface::class, InsightsService::class);
    }
}
