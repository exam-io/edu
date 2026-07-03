<?php

namespace Modules\Institute\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Institute\Application\Services\AcademicSessionService;
use Modules\Institute\Application\Services\InstituteBrandingService;
use Modules\Institute\Application\Services\InstituteConfigurationService;
use Modules\Institute\Application\Services\InstituteProvisioningService;
use Modules\Institute\Application\Services\InstituteRegistrationService;
use Modules\Institute\Application\Services\InstituteService;

class InstituteBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InstituteService::class);
        $this->app->singleton(InstituteRegistrationService::class);
        $this->app->singleton(InstituteProvisioningService::class);
        $this->app->singleton(InstituteBrandingService::class);
        $this->app->singleton(InstituteConfigurationService::class);
        $this->app->singleton(AcademicSessionService::class);
    }
}
