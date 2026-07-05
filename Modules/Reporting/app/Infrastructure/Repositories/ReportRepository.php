<?php

namespace Modules\Reporting\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Reporting\Application\Contracts\ReportRepositoryInterface;
use Modules\Reporting\Application\DTOs\ReportQueryData;
use Modules\Reporting\Domain\Models\ReportExecution;
use Modules\Reporting\Domain\Models\ReportSchedule;
use Modules\Reporting\Domain\Models\ReportTemplate;

class ReportRepository implements ReportRepositoryInterface
{
    public function paginateTemplates(int $tenantId, ReportQueryData $query): LengthAwarePaginator
    {
        $builder = ReportTemplate::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if ($query->q !== null && $query->q !== '') {
            $builder->where(function ($q) use ($query): void {
                $q->where('name', 'like', '%' . $query->q . '%')
                    ->orWhere('slug', 'like', '%' . $query->q . '%')
                    ->orWhere('source', 'like', '%' . $query->q . '%');
            });
        }

        if ($query->status === 'active') {
            $builder->where('is_active', true);
        }

        if ($query->status === 'inactive') {
            $builder->where('is_active', false);
        }

        return $builder->paginate($query->perPage);
    }

    public function createTemplate(array $attributes): ReportTemplate
    {
        return ReportTemplate::query()->create($attributes);
    }

    public function findTemplate(int $tenantId, int $id): ?ReportTemplate
    {
        return ReportTemplate::query()->where('tenant_id', $tenantId)->find($id);
    }

    public function createExecution(array $attributes): ReportExecution
    {
        return ReportExecution::query()->create($attributes);
    }

    public function findExecution(int $tenantId, int $id): ?ReportExecution
    {
        return ReportExecution::query()->where('tenant_id', $tenantId)->find($id);
    }

    public function updateExecution(ReportExecution $execution, array $attributes): ReportExecution
    {
        $execution->fill($attributes);
        $execution->save();

        return $execution;
    }

    public function createExport(array $attributes): void
    {
        \Modules\Reporting\Domain\Models\ReportExport::query()->create($attributes);
    }

    public function createSchedule(array $attributes): ReportSchedule
    {
        return ReportSchedule::query()->create($attributes);
    }

    public function dueSchedules(): Collection
    {
        return ReportSchedule::query()
            ->where('is_active', true)
            ->where('next_run_at', '<=', now())
            ->get();
    }

    public function updateSchedule(ReportSchedule $schedule, array $attributes): ReportSchedule
    {
        $schedule->fill($attributes);
        $schedule->save();

        return $schedule;
    }
}
