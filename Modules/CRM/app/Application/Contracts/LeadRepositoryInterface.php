<?php

namespace Modules\CRM\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\CRM\Domain\Models\Lead;

interface LeadRepositoryInterface
{
    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id): ?Lead;

    public function create(array $attributes): Lead;

    public function update(Lead $lead, array $attributes): Lead;

    public function delete(Lead $lead): void;

    public function createActivity(int $tenantId, int $leadId, string $type, string $message, ?int $actorUserId = null): void;
}
