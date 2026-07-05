<?php

namespace Modules\Reporting\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Reporting\Domain\Events\ReportRequested;
use Modules\Reporting\Jobs\GenerateReportJob;

class QueueReportGeneration implements ShouldQueue
{
    public function handle(ReportRequested $event): void
    {
        GenerateReportJob::dispatch($event->tenantId, $event->executionId);
    }
}
