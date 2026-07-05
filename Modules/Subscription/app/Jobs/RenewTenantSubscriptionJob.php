<?php

namespace Modules\Subscription\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Subscription\Domain\Models\TenantSubscription;

class RenewTenantSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly int $subscriptionId,
    ) {}

    public function handle(): void
    {
        $subscription = TenantSubscription::query()
            ->where('tenant_id', $this->tenantId)
            ->find($this->subscriptionId);

        if (! $subscription instanceof TenantSubscription) {
            return;
        }

        $renewsAt = $subscription->renews_at ?? now();
        $subscription->update([
            'status' => 'active',
            'renews_at' => $renewsAt->copy()->addMonth(),
            'ends_at' => null,
        ]);
    }
}
