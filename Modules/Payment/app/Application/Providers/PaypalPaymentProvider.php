<?php

namespace Modules\Payment\Application\Providers;

class PaypalPaymentProvider extends NullPaymentProvider
{
    public function providerKey(): string
    {
        return 'paypal';
    }
}
