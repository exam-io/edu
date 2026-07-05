<?php

namespace Modules\Insights\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Insights\Application\Contracts\InsightEngineInterface;
use Modules\Insights\Application\Contracts\InsightRepositoryInterface;
use Modules\Insights\Application\Contracts\InsightsServiceInterface;
use Modules\Insights\Application\DTOs\InsightQueryData;
use Modules\Tenant\Application\Services\TenantContextService;

class InsightsService implements InsightsServiceInterface
{
    public function __construct(
        private readonly InsightRepositoryInterface $repository,
        private readonly InsightEngineInterface $engine,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function listGenerated(InsightQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginateGenerated($this->tenantId(), $query);
    }

    public function generateNow(): int
    {
        return $this->engine->generateForTenant($this->tenantId());
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
