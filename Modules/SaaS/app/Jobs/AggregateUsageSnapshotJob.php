<?php

namespace Modules\SaaS\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Billing\Domain\Models\Invoice;
use Modules\Subscription\Domain\Models\TenantSubscription;
use Modules\SaaS\Domain\Models\UsageCounter;
use Modules\SaaS\Domain\Models\UsageSnapshot;

class AggregateUsageSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly string $snapshotDate,
    ) {}

    public function handle(): void
    {
        $periodKey = date('Y-m', strtotime($this->snapshotDate));
        $usageRows = UsageCounter::query()
            ->where('tenant_id', $this->tenantId)
            ->where('period_key', $periodKey)
            ->get(['metric_key', 'counter']);

        $metrics = [];
        foreach ($usageRows as $row) {
            $metrics[$row->metric_key] = (float) $row->counter;
        }

        $mrr = (float) Invoice::query()
            ->where('tenant_id', $this->tenantId)
            ->where('status', 'paid')
            ->where('issued_at', '>=', now()->startOfMonth())
            ->sum('total_amount');

        $activeSubscribers = (int) TenantSubscription::query()
            ->where('tenant_id', $this->tenantId)
            ->whereIn('status', ['trialing', 'active', 'past_due'])
            ->count();

        $snapshot = UsageSnapshot::query()
            ->where('tenant_id', $this->tenantId)
            ->whereDate('snapshot_date', $this->snapshotDate)
            ->first();

        if ($snapshot instanceof UsageSnapshot) {
            $snapshot->update([
                'metrics' => $metrics,
                'mrr' => $mrr,
                'arr' => $mrr * 12,
                'active_subscribers' => $activeSubscribers,
                'meta' => ['period_key' => $periodKey],
            ]);
        }
    }
}
