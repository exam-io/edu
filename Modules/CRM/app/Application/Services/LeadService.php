<?php

namespace Modules\CRM\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\CRM\Application\Contracts\LeadRepositoryInterface;
use Modules\CRM\Application\Contracts\LeadServiceInterface;
use Modules\CRM\Application\DTOs\LeadMutationData;
use Modules\CRM\Application\DTOs\LeadQueryData;
use Modules\CRM\Domain\Events\LeadCreated;
use Modules\CRM\Domain\Events\LeadStatusChanged;
use Modules\CRM\Domain\Models\Lead;
use Modules\Tenant\Application\Services\TenantContextService;

class LeadService implements LeadServiceInterface
{
    public function __construct(
        private readonly LeadRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(LeadQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query->perPage, [
            'status' => $query->status,
            'source' => $query->source,
            'search' => $query->search,
        ]);
    }

    public function find(int $id): Lead
    {
        $lead = $this->repository->findForTenant($this->tenantId(), $id);
        abort_if($lead === null, 404, 'Lead not found.');

        return $lead;
    }

    public function create(LeadMutationData $data): Lead
    {
        $tenantId = $this->tenantId();
        $lead = $this->repository->create(array_merge($data->toArray(), ['tenant_id' => $tenantId]));

        $this->repository->createActivity($tenantId, $lead->id, 'created', 'Lead created.', Auth::id());
        Event::dispatch(new LeadCreated($lead->id, $tenantId));

        return $lead;
    }

    public function update(int $id, LeadMutationData $data): Lead
    {
        $lead = $this->find($id);
        $oldStatus = $lead->status;

        $updated = $this->repository->update($lead, $data->toArray());
        $tenantId = $this->tenantId();

        $this->repository->createActivity($tenantId, $updated->id, 'updated', 'Lead updated.', Auth::id());

        if ($oldStatus !== $updated->status) {
            $this->repository->createActivity(
                $tenantId,
                $updated->id,
                'status_changed',
                'Lead status changed from ' . $oldStatus . ' to ' . $updated->status . '.',
                Auth::id()
            );

            Event::dispatch(new LeadStatusChanged($updated->id, $tenantId, $oldStatus, $updated->status));
        }

        return $updated;
    }

    public function delete(int $id): void
    {
        $lead = $this->find($id);
        $this->repository->delete($lead);
        $this->repository->createActivity($this->tenantId(), $lead->id, 'deleted', 'Lead deleted.', Auth::id());
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
