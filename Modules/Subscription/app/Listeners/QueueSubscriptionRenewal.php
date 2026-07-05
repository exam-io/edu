<?php

namespace Modules\Subscription\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Subscription\Domain\Events\SubscriptionRenewalRequested;
use Modules\Subscription\Jobs\RenewTenantSubscriptionJob;

class QueueSubscriptionRenewal implements ShouldQueue
{
    public function handle(SubscriptionRenewalRequested $event): void
    {
        RenewTenantSubscriptionJob::dispatch($event->tenantId, $event->subscriptionId);
    }
}
