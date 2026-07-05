<?php

namespace Modules\Insights\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Analytics\Domain\Models\MetricSnapshot;
use Modules\Insights\Application\Contracts\InsightRepositoryInterface;
use Modules\Insights\Application\DTOs\InsightQueryData;
use Modules\Insights\Domain\Models\GeneratedInsight;
use Modules\Insights\Domain\Models\InsightRule;

class InsightRepository implements InsightRepositoryInterface
{
    public function paginateGenerated(int $tenantId, InsightQueryData $query): LengthAwarePaginator
    {
        $builder = GeneratedInsight::query()
            ->where('tenant_id', $tenantId)
            ->latest('generated_at');

        if ($query->q !== null && $query->q !== '') {
            $builder->where(function ($q) use ($query): void {
                $q->where('title', 'like', '%' . $query->q . '%')
                    ->orWhere('summary', 'like', '%' . $query->q . '%');
            });
        }

        if ($query->severity !== null && $query->severity !== '') {
            $builder->where('severity', $query->severity);
        }

        return $builder->paginate($query->perPage);
    }

    public function activeRules(int $tenantId): Collection
    {
        return InsightRule::query()
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->get();
    }

    public function latestMetricValue(int $tenantId, string $metricKey): ?float
    {
        $row = MetricSnapshot::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('metric_key', $metricKey)
            ->latest('generated_at')
            ->first();

        return $row instanceof MetricSnapshot ? (float) $row->metric_value : null;
    }

    public function createGenerated(array $attributes): GeneratedInsight
    {
        return GeneratedInsight::query()->create($attributes);
    }
}
