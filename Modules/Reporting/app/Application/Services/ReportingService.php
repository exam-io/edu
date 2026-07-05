<?php

namespace Modules\Reporting\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Modules\Reporting\Application\Contracts\ReportRepositoryInterface;
use Modules\Reporting\Application\Contracts\ReportingServiceInterface;
use Modules\Reporting\Application\DTOs\ReportQueryData;
use Modules\Reporting\Application\DTOs\ReportTemplateData;
use Modules\Reporting\Application\DTOs\ScheduleReportData;
use Modules\Reporting\Domain\Events\ReportRequested;
use Modules\Reporting\Domain\Events\ReportScheduled;
use Modules\Reporting\Domain\Models\ReportExecution;
use Modules\Reporting\Domain\Models\ReportSchedule;
use Modules\Reporting\Domain\Models\ReportTemplate;
use Modules\Tenant\Application\Services\TenantContextService;

class ReportingService implements ReportingServiceInterface
{
    public function __construct(
        private readonly ReportRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function listTemplates(ReportQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginateTemplates($this->tenantId(), $query);
    }

    public function createTemplate(ReportTemplateData $data): ReportTemplate
    {
        return $this->repository->createTemplate([
            'tenant_id' => $this->tenantId(),
            'name' => $data->name,
            'slug' => $data->slug,
            'source' => $data->source,
            'definition' => $data->definition,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);
    }

    public function requestRun(int $templateId, array $filters = []): ReportExecution
    {
        $template = $this->repository->findTemplate($this->tenantId(), $templateId);
        if (! $template instanceof ReportTemplate) {
            throw new \RuntimeException('Report template not found.');
        }

        $execution = $this->repository->createExecution([
            'tenant_id' => $this->tenantId(),
            'report_template_id' => $template->id,
            'status' => 'queued',
            'filters' => $filters,
            'requested_by' => auth()->id(),
        ]);

        Event::dispatch(new ReportRequested($execution->id, $execution->tenant_id));

        return $execution;
    }

    public function schedule(ScheduleReportData $data): ReportSchedule
    {
        $schedule = $this->repository->createSchedule([
            'tenant_id' => $this->tenantId(),
            'report_template_id' => $data->reportTemplateId,
            'frequency' => $data->frequency,
            'next_run_at' => $data->nextRunAt,
            'filters' => $data->filters,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        Event::dispatch(new ReportScheduled($schedule->id, $schedule->tenant_id));

        return $schedule;
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
