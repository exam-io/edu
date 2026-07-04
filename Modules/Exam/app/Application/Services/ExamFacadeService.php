<?php

namespace Modules\Exam\Application\Services;

use Modules\Assessment\Domain\Models\Assessment;
use Modules\Exam\Application\Contracts\ExamFacadeServiceInterface;
use Modules\Tenant\Application\Services\TenantContextService;

class ExamFacadeService implements ExamFacadeServiceInterface
{
    public function __construct(private readonly TenantContextService $tenantContext)
    {
    }

    public function overview(): array
    {
        $tenantId = $this->tenantContext->requiredTenantId();

        $baseQuery = Assessment::query()->where('tenant_id', $tenantId);

        return [
            'total_assessments' => (clone $baseQuery)->count(),
            'published_assessments' => (clone $baseQuery)->where('status', 'published')->count(),
            'exam_type_breakdown' => (clone $baseQuery)
                ->selectRaw('type, count(*) as total')
                ->groupBy('type')
                ->orderBy('type')
                ->get()
                ->map(fn ($row) => ['type' => $row->type, 'total' => (int) $row->total])
                ->values()
                ->all(),
        ];
    }
}
