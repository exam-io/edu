<?php

namespace Modules\Admissions\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\Admissions\Application\Contracts\AdmissionApplicationRepositoryInterface;
use Modules\Admissions\Application\Contracts\AdmissionApplicationServiceInterface;
use Modules\Admissions\Application\DTOs\AdmissionApplicationData;
use Modules\Admissions\Application\DTOs\AdmissionApplicationQueryData;
use Modules\Admissions\Application\DTOs\AdmissionStatusTransitionData;
use Modules\Admissions\Domain\Events\AdmissionApplicationStatusChanged;
use Modules\Admissions\Domain\Models\AdmissionApplication;
use Modules\Tenant\Application\Services\TenantContextService;

class AdmissionApplicationService implements AdmissionApplicationServiceInterface
{
    public function __construct(
        private readonly AdmissionApplicationRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(AdmissionApplicationQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query->perPage, [
            'status' => $query->status,
            'program' => $query->program,
            'search' => $query->search,
        ]);
    }

    public function find(int $id): AdmissionApplication
    {
        $application = $this->repository->findForTenant($this->tenantId(), $id);
        abort_if($application === null, 404, 'Admission application not found.');

        return $application;
    }

    public function create(AdmissionApplicationData $data): AdmissionApplication
    {
        $application = $this->repository->create(array_merge($data->toArray(), [
            'tenant_id' => $this->tenantId(),
            'submitted_at' => now(),
        ]));

        $this->repository->logStatusHistory($this->tenantId(), $application->id, null, $application->status, Auth::id());

        return $application;
    }

    public function changeStatus(int $id, AdmissionStatusTransitionData $data): AdmissionApplication
    {
        $application = $this->find($id);
        $from = $application->status;

        $updated = $this->repository->update($application, [
            'status' => $data->toStatus,
            'reviewed_at' => now(),
        ]);

        $tenantId = $this->tenantId();
        $this->repository->logStatusHistory($tenantId, $updated->id, $from, $data->toStatus, Auth::id());
        Event::dispatch(new AdmissionApplicationStatusChanged($updated->id, $tenantId, $from, $data->toStatus));

        return $updated;
    }

    public function delete(int $id): void
    {
        $this->repository->delete($this->find($id));
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
