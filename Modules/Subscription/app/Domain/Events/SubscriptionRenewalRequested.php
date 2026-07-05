<?php

namespace Modules\Subscription\Domain\Events;

readonly class SubscriptionRenewalRequested
{
    public function __construct(
        public int $tenantId,
        public int $subscriptionId,
    ) {}
}
