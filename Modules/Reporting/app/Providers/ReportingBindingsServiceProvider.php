<?php

namespace Modules\Reporting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Reporting\Application\Contracts\ExportServiceInterface;
use Modules\Reporting\Application\Contracts\ReportEngineInterface;
use Modules\Reporting\Application\Contracts\ReportRepositoryInterface;
use Modules\Reporting\Application\Contracts\ReportingServiceInterface;
use Modules\Reporting\Application\Services\ExportService;
use Modules\Reporting\Application\Services\ReportEngine;
use Modules\Reporting\Application\Services\ReportingService;
use Modules\Reporting\Infrastructure\Repositories\ReportRepository;

class ReportingBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(ReportEngineInterface::class, ReportEngine::class);
        $this->app->bind(ExportServiceInterface::class, ExportService::class);
        $this->app->bind(ReportingServiceInterface::class, ReportingService::class);
    }
}
