<?php

namespace Modules\Subscription\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Subscription\Application\Contracts\SubscriptionServiceInterface;
use Modules\Subscription\Application\Services\SubscriptionService;

class SubscriptionBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);
    }
}
