<?php

namespace Modules\Admissions\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Admissions\Application\Contracts\AdmissionApplicationRepositoryInterface;
use Modules\Admissions\Application\Contracts\AdmissionApplicationServiceInterface;
use Modules\Admissions\Application\Services\AdmissionApplicationService;
use Modules\Admissions\Infrastructure\Repositories\AdmissionApplicationRepository;

class AdmissionsBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AdmissionApplicationRepositoryInterface::class, AdmissionApplicationRepository::class);
        $this->app->bind(AdmissionApplicationServiceInterface::class, AdmissionApplicationService::class);
    }
}
