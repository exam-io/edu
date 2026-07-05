<?php

namespace Modules\Payment\Application\Contracts;

interface PaymentProviderResolverInterface
{
    public function resolve(?string $provider = null): PaymentProviderInterface;
}
