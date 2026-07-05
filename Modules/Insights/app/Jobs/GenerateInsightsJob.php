<?php

namespace Modules\Insights\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Insights\Application\Contracts\InsightEngineInterface;

class GenerateInsightsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
    ) {}

    public function handle(InsightEngineInterface $engine): void
    {
        $engine->generateForTenant($this->tenantId);
    }
}
