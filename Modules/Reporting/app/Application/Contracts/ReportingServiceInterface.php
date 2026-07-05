<?php

namespace Modules\Reporting\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Reporting\Application\DTOs\ReportQueryData;
use Modules\Reporting\Application\DTOs\ReportTemplateData;
use Modules\Reporting\Application\DTOs\ScheduleReportData;
use Modules\Reporting\Domain\Models\ReportExecution;
use Modules\Reporting\Domain\Models\ReportSchedule;
use Modules\Reporting\Domain\Models\ReportTemplate;

interface ReportingServiceInterface
{
    public function listTemplates(ReportQueryData $query): LengthAwarePaginator;

    public function createTemplate(ReportTemplateData $data): ReportTemplate;

    public function requestRun(int $templateId, array $filters = []): ReportExecution;

    public function schedule(ScheduleReportData $data): ReportSchedule;
}
