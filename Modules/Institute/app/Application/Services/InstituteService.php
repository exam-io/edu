<?php

namespace Modules\Institute\Application\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Institute\Domain\Models\Institute;
use Modules\Tenant\Application\Services\TenantContextService;

class InstituteService
{
    public function __construct(
        private readonly TenantContextService $tenantContextService,
    ) {}

    public function getCurrentForTenant(): ?Institute
    {
        $tenantId = $this->tenantContextService->tenantId();

        if ($tenantId === null) {
            return null;
        }

        return Institute::query()
            ->where('tenant_id', $tenantId)
            ->with(['currentAcademicSession'])
            ->latest('id')
            ->first();
    }

    public function getByIdForTenant(int $instituteId): ?Institute
    {
        $tenantId = $this->tenantContextService->requiredTenantId();

        return Institute::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($instituteId)
            ->with(['currentAcademicSession'])
            ->first();
    }

    public function getByIdForTenantOrFail(int $instituteId): Institute
    {
        $institute = $this->getByIdForTenant($instituteId);

        if ($institute === null) {
            throw (new ModelNotFoundException())->setModel(Institute::class, [$instituteId]);
        }

        return $institute;
    }

    public function updateInstitute(Institute $institute, array $attributes): Institute
    {
        $payload = array_filter([
            'name' => $attributes['name'] ?? null,
            'code' => $attributes['code'] ?? null,
            'email' => $attributes['email'] ?? null,
            'phone' => $attributes['phone'] ?? null,
            'website' => $attributes['website'] ?? null,
            'description' => $attributes['description'] ?? null,
            'address' => $attributes['address'] ?? null,
        ], static fn ($value) => $value !== null);

        if ($payload !== []) {
            $institute->update($payload);
        }

        return $institute->refresh();
    }
}
