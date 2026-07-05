<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Payment\Application\Contracts\PaymentProviderResolverInterface;
use Modules\Payment\Application\Contracts\PaymentServiceInterface;
use Modules\Payment\Application\Services\PaymentProviderResolver;
use Modules\Payment\Application\Services\PaymentService;

class PaymentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(PaymentProviderResolverInterface::class, PaymentProviderResolver::class);
    }
}
