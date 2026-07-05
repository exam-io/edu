<?php

namespace Modules\Monitoring\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Monitoring\Application\Contracts\MetricsServiceInterface;
use Modules\Monitoring\Application\DTOs\MetricsQueryData;
use Modules\Monitoring\Domain\Events\MetricThresholdBreached;
use Modules\Monitoring\Domain\Models\AlertIncident;
use Modules\Monitoring\Domain\Models\AlertRule;
use Modules\Monitoring\Domain\Models\MetricSnapshot;

class MetricsService implements MetricsServiceInterface
{
    public function metrics(int $tenantId, MetricsQueryData $query): LengthAwarePaginator
    {
        $builder = MetricSnapshot::query()->where('tenant_id', $tenantId)->latest('id');

        if ($query->metricKey !== null && $query->metricKey !== '') {
            $builder->where('metric_key', $query->metricKey);
        }

        if ($query->periodKey !== null && $query->periodKey !== '') {
            $builder->where('period_key', $query->periodKey);
        }

        return $builder->paginate($query->perPage);
    }

    public function aggregateSnapshot(int $tenantId, array $payload): void
    {
        $metricKey = (string) ($payload['metric_key'] ?? 'unknown');
        $periodKey = (string) ($payload['period_key'] ?? now()->format('Y-m-d-H'));
        $value = (float) ($payload['value'] ?? 0);

        MetricSnapshot::query()->create([
            'tenant_id' => $tenantId,
            'metric_key' => $metricKey,
            'period_key' => $periodKey,
            'value' => $value,
            'meta' => is_array($payload['meta'] ?? null) ? $payload['meta'] : [],
        ]);

        $rules = AlertRule::query()->where('tenant_id', $tenantId)->where('metric_key', $metricKey)->where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($this->matchesRule($value, $rule->operator, (float) $rule->threshold)) {
                AlertIncident::query()->create([
                    'tenant_id' => $tenantId,
                    'metric_key' => $metricKey,
                    'severity' => $rule->severity,
                    'status' => 'open',
                    'triggered_at' => now(),
                    'resolved_at' => null,
                    'meta' => ['value' => $value, 'rule_id' => $rule->id],
                ]);

                event(new MetricThresholdBreached($tenantId, $metricKey, $rule->severity, ['value' => $value]));
            }
        }
    }

    public function upsertAlertRule(int $tenantId, array $payload): AlertRule
    {
        $rule = AlertRule::query()->firstOrNew(['id' => isset($payload['id']) ? (int) $payload['id'] : null]);
        $rule->fill([
            'tenant_id' => $tenantId,
            'metric_key' => (string) $payload['metric_key'],
            'operator' => (string) ($payload['operator'] ?? '>='),
            'threshold' => (float) $payload['threshold'],
            'severity' => (string) ($payload['severity'] ?? 'warning'),
            'is_active' => (bool) ($payload['is_active'] ?? true),
            'meta' => is_array($payload['meta'] ?? null) ? $payload['meta'] : [],
        ]);
        $rule->save();

        return $rule->refresh();
    }

    private function matchesRule(float $value, string $operator, float $threshold): bool
    {
        return match ($operator) {
            '>' => $value > $threshold,
            '>=' => $value >= $threshold,
            '<' => $value < $threshold,
            '<=' => $value <= $threshold,
            '=' => $value === $threshold,
            default => false,
        };
    }
}
