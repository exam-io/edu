<?php

namespace Modules\Admissions\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Admissions\Domain\Models\AdmissionApplication;

interface AdmissionApplicationRepositoryInterface
{
    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id): ?AdmissionApplication;

    public function firstByLead(int $tenantId, int $leadId): ?AdmissionApplication;

    public function create(array $attributes): AdmissionApplication;

    public function update(AdmissionApplication $application, array $attributes): AdmissionApplication;

    public function delete(AdmissionApplication $application): void;

    public function logStatusHistory(int $tenantId, int $applicationId, ?string $fromStatus, string $toStatus, ?int $actorUserId = null): void;
}
