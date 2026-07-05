<?php

namespace Modules\Monitoring\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Monitoring\Application\Contracts\MetricsServiceInterface;

class AggregateMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly array $payload,
    ) {}

    public function handle(MetricsServiceInterface $service): void
    {
        $service->aggregateSnapshot($this->tenantId, $this->payload);
    }
}
