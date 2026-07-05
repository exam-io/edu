<?php

namespace Modules\Payment\Application\Providers;

class StripePaymentProvider extends NullPaymentProvider
{
    public function providerKey(): string
    {
        return 'stripe';
    }
}
