<?php

namespace Modules\Reporting\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Reporting\Application\DTOs\ReportQueryData;
use Modules\Reporting\Domain\Models\ReportExecution;
use Modules\Reporting\Domain\Models\ReportSchedule;
use Modules\Reporting\Domain\Models\ReportTemplate;

interface ReportRepositoryInterface
{
    public function paginateTemplates(int $tenantId, ReportQueryData $query): LengthAwarePaginator;

    public function createTemplate(array $attributes): ReportTemplate;

    public function findTemplate(int $tenantId, int $id): ?ReportTemplate;

    public function createExecution(array $attributes): ReportExecution;

    public function findExecution(int $tenantId, int $id): ?ReportExecution;

    public function updateExecution(ReportExecution $execution, array $attributes): ReportExecution;

    public function createExport(array $attributes): void;

    public function createSchedule(array $attributes): ReportSchedule;

    public function dueSchedules(): Collection;

    public function updateSchedule(ReportSchedule $schedule, array $attributes): ReportSchedule;
}
