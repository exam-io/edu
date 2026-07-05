<?php

namespace Modules\Subscription\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Subscription\Application\Contracts\SubscriptionServiceInterface;
use Modules\Subscription\Application\DTOs\PlanMutationData;
use Modules\Subscription\Application\DTOs\PlanQueryData;
use Modules\Subscription\Application\DTOs\TenantSubscriptionMutationData;
use Modules\Subscription\Domain\Events\SubscriptionRenewalRequested;
use Modules\Subscription\Domain\Models\SubscriptionPlan;
use Modules\Subscription\Domain\Models\TenantSubscription;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function plans(PlanQueryData $query): LengthAwarePaginator
    {
        $builder = SubscriptionPlan::query()->latest('id');

        if ($query->status !== null && $query->status !== '') {
            $builder->where('is_active', $query->status === 'active');
        }

        if ($query->search !== null && $query->search !== '') {
            $builder->where(fn ($q) => $q->where('code', 'like', '%' . $query->search . '%')->orWhere('name', 'like', '%' . $query->search . '%'));
        }

        return $builder->paginate($query->perPage);
    }

    public function upsertPlan(PlanMutationData $data): SubscriptionPlan
    {
        $plan = SubscriptionPlan::query()->firstOrNew(['id' => $data->id]);
        $plan->fill([
            'code' => $data->code,
            'name' => $data->name,
            'description' => $data->description,
            'billing_interval' => $data->billingInterval,
            'price_amount' => $data->priceAmount,
            'currency' => $data->currency,
            'quota' => $data->quota,
            'is_active' => $data->isActive,
            'meta' => $data->meta,
        ]);
        $plan->save();

        return $plan->refresh();
    }

    public function tenantSubscriptions(int $tenantId, PlanQueryData $query): LengthAwarePaginator
    {
        $builder = TenantSubscription::query()->with('plan')->where('tenant_id', $tenantId)->latest('id');

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        return $builder->paginate($query->perPage);
    }

    public function currentSubscription(int $tenantId): ?TenantSubscription
    {
        return TenantSubscription::query()
            ->with('plan')
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['trialing', 'active', 'past_due'])
            ->latest('id')
            ->first();
    }

    public function upsertTenantSubscription(int $tenantId, TenantSubscriptionMutationData $data): TenantSubscription
    {
        $subscription = TenantSubscription::query()
            ->where('tenant_id', $tenantId)
            ->latest('id')
            ->firstOrNew(['tenant_id' => $tenantId]);

        $subscription->fill([
            'plan_id' => $data->planId,
            'status' => $data->status,
            'starts_at' => $data->startsAt ?? now(),
            'renews_at' => $data->renewsAt,
            'ends_at' => $data->endsAt,
            'meta' => $data->meta,
        ]);
        $subscription->save();

        return $subscription->refresh()->load('plan');
    }

    public function requestRenewal(int $tenantId): ?TenantSubscription
    {
        $subscription = $this->currentSubscription($tenantId);
        if (! $subscription instanceof TenantSubscription) {
            return null;
        }

        event(new SubscriptionRenewalRequested($tenantId, $subscription->id));

        return $subscription;
    }
}
