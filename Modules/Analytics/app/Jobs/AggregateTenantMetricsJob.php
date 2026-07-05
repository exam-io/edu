<?php

namespace Modules\Analytics\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Analytics\Application\Contracts\AnalyticsEngineInterface;

class AggregateTenantMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly ?string $periodStart = null,
        private readonly ?string $periodEnd = null,
    ) {}

    public function handle(AnalyticsEngineInterface $engine): void
    {
        $start = $this->periodStart !== null ? new \DateTimeImmutable($this->periodStart) : now()->subHour();
        $end = $this->periodEnd !== null ? new \DateTimeImmutable($this->periodEnd) : now();

        $engine->aggregateForTenant($this->tenantId, $start, $end);
    }
}
