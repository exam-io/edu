<?php

namespace Tests\Unit\Modules\Monitoring;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\Application\Services\MetricsService;
use Modules\Monitoring\Domain\Models\AlertIncident;
use Modules\Monitoring\Domain\Models\AlertRule;
use Modules\Monitoring\Domain\Models\MetricSnapshot;
use Modules\Tenant\Domain\Models\Tenant;
use Tests\TestCase;

class MetricsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_aggregate_snapshot_creates_incident_when_threshold_matches(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Monitoring Unit Tenant',
            'slug' => 'monitoring-unit',
            'domain' => 'monitoring-unit.localhost',
            'status' => 'active',
        ]);

        AlertRule::query()->create([
            'tenant_id' => $tenant->id,
            'metric_key' => 'queue_backlog',
            'operator' => '>=',
            'threshold' => 20,
            'severity' => 'critical',
            'is_active' => true,
            'meta' => [],
        ]);

        $service = new MetricsService();
        $service->aggregateSnapshot($tenant->id, [
            'metric_key' => 'queue_backlog',
            'period_key' => now()->format('Y-m-d-H'),
            'value' => 25,
            'meta' => ['source' => 'unit-test'],
        ]);

        $this->assertSame(1, MetricSnapshot::query()->count());
        $this->assertSame(1, AlertIncident::query()->count());
        $this->assertSame('critical', AlertIncident::query()->firstOrFail()->severity);
    }
}
