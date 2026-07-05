<?php

namespace Modules\Insights\Application\Services;

use Illuminate\Support\Facades\Event;
use Modules\Insights\Application\Contracts\InsightEngineInterface;
use Modules\Insights\Application\Contracts\InsightRepositoryInterface;
use Modules\Insights\Domain\Events\InsightGenerated;
use Modules\Insights\Domain\Models\InsightRule;

class InsightEngine implements InsightEngineInterface
{
    public function __construct(
        private readonly InsightRepositoryInterface $repository,
    ) {}

    public function generateForTenant(int $tenantId): int
    {
        $created = 0;
        $rules = $this->repository->activeRules($tenantId);

        foreach ($rules as $rule) {
            if (! $rule instanceof InsightRule) {
                continue;
            }

            $value = $this->repository->latestMetricValue($tenantId, $rule->metric_key);
            if ($value === null) {
                continue;
            }

            if (! $this->matches($value, (string) $rule->operator, (float) $rule->threshold)) {
                continue;
            }

            $insight = $this->repository->createGenerated([
                'tenant_id' => $tenantId,
                'insight_rule_id' => $rule->id,
                'title' => 'Threshold crossed: ' . $rule->name,
                'summary' => sprintf('Metric %s is %.4f and crossed threshold %.4f.', $rule->metric_key, $value, (float) $rule->threshold),
                'severity' => $rule->severity,
                'context_payload' => [
                    'metric_key' => $rule->metric_key,
                    'value' => $value,
                    'threshold' => (float) $rule->threshold,
                    'operator' => $rule->operator,
                ],
                'generated_at' => now(),
            ]);

            Event::dispatch(new InsightGenerated($insight->id, $tenantId));
            $created++;
        }

        return $created;
    }

    private function matches(float $value, string $operator, float $threshold): bool
    {
        return match ($operator) {
            '>' => $value > $threshold,
            '>=' => $value >= $threshold,
            '<' => $value < $threshold,
            '<=' => $value <= $threshold,
            '=' => abs($value - $threshold) < 0.00001,
            default => false,
        };
    }
}
