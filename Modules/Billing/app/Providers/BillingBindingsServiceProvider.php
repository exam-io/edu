<?php

namespace Modules\Billing\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Billing\Application\Contracts\BillingServiceInterface;
use Modules\Billing\Application\Services\BillingService;

class BillingBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BillingServiceInterface::class, BillingService::class);
    }
}
