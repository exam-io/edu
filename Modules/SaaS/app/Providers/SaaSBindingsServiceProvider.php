<?php

namespace Modules\SaaS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\SaaS\Application\Contracts\SaaSServiceInterface;
use Modules\SaaS\Application\Services\SaaSService;

class SaaSBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SaaSServiceInterface::class, SaaSService::class);
    }
}
