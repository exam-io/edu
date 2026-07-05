<?php

namespace Modules\Branding\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Branding\Application\Contracts\BrandingServiceInterface;
use Modules\Branding\Application\Services\BrandingService;
use Modules\Branding\Application\Services\ThemeEngineService;

class BrandingBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BrandingServiceInterface::class, BrandingService::class);
        $this->app->singleton(ThemeEngineService::class);
    }
}
