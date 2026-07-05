<?php

namespace Modules\CRM\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\CRM\Application\DTOs\LeadMutationData;
use Modules\CRM\Application\DTOs\LeadQueryData;
use Modules\CRM\Domain\Models\Lead;

interface LeadServiceInterface
{
    public function list(LeadQueryData $query): LengthAwarePaginator;

    public function find(int $id): Lead;

    public function create(LeadMutationData $data): Lead;

    public function update(int $id, LeadMutationData $data): Lead;

    public function delete(int $id): void;
}
