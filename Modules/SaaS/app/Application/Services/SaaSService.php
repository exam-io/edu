<?php

namespace Modules\SaaS\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Billing\Domain\Models\Invoice;
use Modules\Subscription\Domain\Models\TenantSubscription;
use Modules\SaaS\Application\Contracts\SaaSServiceInterface;
use Modules\SaaS\Application\DTOs\TrackUsageData;
use Modules\SaaS\Application\DTOs\UsageQueryData;
use Modules\SaaS\Domain\Events\UsageSnapshotRequested;
use Modules\SaaS\Domain\Models\UsageCounter;
use Modules\SaaS\Domain\Models\UsageSnapshot;

class SaaSService implements SaaSServiceInterface
{
    public function dashboard(int $tenantId): array
    {
        $mrr = (float) Invoice::query()
            ->where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->where('issued_at', '>=', now()->startOfMonth())
            ->sum('total_amount');

        $activeSubscribers = (int) TenantSubscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['trialing', 'active', 'past_due'])
            ->count();

        $latestSnapshot = UsageSnapshot::query()
            ->where('tenant_id', $tenantId)
            ->latest('snapshot_date')
            ->first();

        return [
            'mrr' => $mrr,
            'arr' => $mrr * 12,
            'active_subscribers' => $activeSubscribers,
            'latest_snapshot' => $latestSnapshot,
        ];
    }

    public function usage(int $tenantId, UsageQueryData $query): LengthAwarePaginator
    {
        $builder = UsageCounter::query()->where('tenant_id', $tenantId)->latest('id');

        if ($query->metricKey !== null && $query->metricKey !== '') {
            $builder->where('metric_key', $query->metricKey);
        }

        if ($query->periodKey !== null && $query->periodKey !== '') {
            $builder->where('period_key', $query->periodKey);
        }

        return $builder->paginate($query->perPage);
    }

    public function trackUsage(int $tenantId, TrackUsageData $data): UsageCounter
    {
        $counter = UsageCounter::query()->firstOrNew([
            'tenant_id' => $tenantId,
            'metric_key' => $data->metricKey,
            'period_key' => $data->periodKey,
        ]);

        $counter->counter = (float) ($counter->counter ?? 0) + $data->incrementBy;
        $counter->meta = $data->meta;
        $counter->save();

        return $counter->refresh();
    }

    public function requestSnapshot(int $tenantId, ?string $snapshotDate = null): UsageSnapshot
    {
        $resolvedDate = $snapshotDate ?: now()->toDateString();

        $snapshot = UsageSnapshot::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'snapshot_date' => $resolvedDate],
            [
                'metrics' => [],
                'mrr' => 0,
                'arr' => 0,
                'active_subscribers' => 0,
                'meta' => ['queued' => true],
            ],
        );

        event(new UsageSnapshotRequested($tenantId, $resolvedDate));

        return $snapshot;
    }
}
