<?php

namespace Modules\Payment\Application\Services;

use InvalidArgumentException;
use Modules\Payment\Application\Contracts\PaymentProviderInterface;
use Modules\Payment\Application\Contracts\PaymentProviderResolverInterface;
use Modules\Payment\Application\Providers\NullPaymentProvider;
use Modules\Payment\Application\Providers\PaypalPaymentProvider;
use Modules\Payment\Application\Providers\StripePaymentProvider;

class PaymentProviderResolver implements PaymentProviderResolverInterface
{
    public function resolve(?string $provider = null): PaymentProviderInterface
    {
        $resolved = strtolower((string) ($provider ?: config('payment.default_provider', 'null')));

        return match ($resolved) {
            'null' => new NullPaymentProvider(),
            'stripe' => new StripePaymentProvider(),
            'paypal' => new PaypalPaymentProvider(),
            default => throw new InvalidArgumentException('Unsupported payment provider.'),
        };
    }
}
