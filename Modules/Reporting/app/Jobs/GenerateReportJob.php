<?php

namespace Modules\Reporting\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Modules\Reporting\Application\Contracts\ExportServiceInterface;
use Modules\Reporting\Application\Contracts\ReportEngineInterface;
use Modules\Reporting\Application\Contracts\ReportRepositoryInterface;
use Modules\Reporting\Domain\Events\ReportGenerated;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly int $executionId,
    ) {}

    public function handle(
        ReportRepositoryInterface $repository,
        ReportEngineInterface $engine,
        ExportServiceInterface $exportService,
    ): void {
        $execution = $repository->findExecution($this->tenantId, $this->executionId);
        if (! $execution instanceof \Modules\Reporting\Domain\Models\ReportExecution) {
            return;
        }

        $repository->updateExecution($execution, [
            'status' => 'running',
            'started_at' => now(),
        ]);

        $result = $engine->generate(
            tenantId: $this->tenantId,
            templateId: $execution->report_template_id,
            filters: is_array($execution->filters) ? $execution->filters : [],
        );

        $rows = is_array($result['rows'] ?? null) ? $result['rows'] : [];
        $format = (string) ($execution->filters['export_format'] ?? 'csv');
        $filePath = $exportService->export($this->tenantId, $this->executionId, $rows, $format);

        $repository->updateExecution($execution, [
            'status' => 'completed',
            'result_payload' => $result,
            'row_count' => count($rows),
            'completed_at' => now(),
        ]);

        $repository->createExport([
            'tenant_id' => $this->tenantId,
            'report_execution_id' => $execution->id,
            'format' => strtolower($format) === 'json' ? 'json' : 'csv',
            'file_path' => $filePath,
            'status' => 'ready',
            'requested_by' => $execution->requested_by,
        ]);

        Event::dispatch(new ReportGenerated($execution->id, $execution->tenant_id));
    }
}
