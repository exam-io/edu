<?php

namespace Modules\FeatureManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FeatureManagement\Application\Contracts\FeatureFlagServiceInterface;
use Modules\FeatureManagement\Application\Services\FeatureFlagService;

class FeatureManagementBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FeatureFlagServiceInterface::class, FeatureFlagService::class);
    }
}
