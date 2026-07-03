<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Academic\Application\Contracts\AcademicStructureServiceInterface;
use Modules\Tenant\Application\Services\TenantContextService;

class AcademicStructureService implements AcademicStructureServiceInterface
{
    public function __construct(
        private readonly TenantContextService $tenantContextService,
    ) {}

    public function tenantId(): int
    {
        return $this->tenantContextService->requiredTenantId();
    }

    public function assertTenantOwned(string $modelClass, int $id): object
    {
        /** @var Model|null $model */
        $model = $modelClass::query()
            ->where('tenant_id', $this->tenantId())
            ->whereKey($id)
            ->first();

        if ($model === null) {
            throw (new ModelNotFoundException())->setModel($modelClass, [$id]);
        }

        return $model;
    }
}
