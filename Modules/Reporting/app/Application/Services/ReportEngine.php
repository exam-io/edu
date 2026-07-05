<?php

namespace Modules\Reporting\Application\Services;

use Modules\Analytics\Domain\Models\MetricSnapshot;
use Modules\Reporting\Application\Contracts\ReportEngineInterface;

class ReportEngine implements ReportEngineInterface
{
    public function generate(int $tenantId, int $templateId, array $filters): array
    {
        $metricKey = (string) ($filters['metric_key'] ?? 'events.count');
        $limit = max(1, min(500, (int) ($filters['limit'] ?? 100)));

        $rows = MetricSnapshot::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('metric_key', $metricKey)
            ->latest('generated_at')
            ->limit($limit)
            ->get()
            ->map(static fn (MetricSnapshot $snapshot): array => [
                'metric_key' => $snapshot->metric_key,
                'dimension_key' => $snapshot->dimension_key,
                'dimension_value' => $snapshot->dimension_value,
                'metric_value' => (float) $snapshot->metric_value,
                'generated_at' => $snapshot->generated_at?->toIso8601String(),
            ])
            ->values()
            ->all();

        return [
            'template_id' => $templateId,
            'rows' => $rows,
        ];
    }
}
