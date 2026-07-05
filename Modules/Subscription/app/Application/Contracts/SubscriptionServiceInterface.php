<?php

namespace Modules\Subscription\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Subscription\Application\DTOs\PlanMutationData;
use Modules\Subscription\Application\DTOs\PlanQueryData;
use Modules\Subscription\Application\DTOs\TenantSubscriptionMutationData;
use Modules\Subscription\Domain\Models\SubscriptionPlan;
use Modules\Subscription\Domain\Models\TenantSubscription;

interface SubscriptionServiceInterface
{
    public function plans(PlanQueryData $query): LengthAwarePaginator;

    public function upsertPlan(PlanMutationData $data): SubscriptionPlan;

    public function tenantSubscriptions(int $tenantId, PlanQueryData $query): LengthAwarePaginator;

    public function currentSubscription(int $tenantId): ?TenantSubscription;

    public function upsertTenantSubscription(int $tenantId, TenantSubscriptionMutationData $data): TenantSubscription;

    public function requestRenewal(int $tenantId): ?TenantSubscription;
}
