<?php

namespace Modules\Parent\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Modules\Parent\Application\Contracts\ParentServiceInterface;
use Modules\Parent\Application\Contracts\ParentTenantRepositoryInterface;
use Modules\Parent\Application\DTOs\ParentListQueryData;
use Modules\Parent\Application\DTOs\ParentMutationData;
use Modules\Parent\Domain\Events\ParentCreated;
use Modules\Parent\Domain\Models\ParentProfile;
use Modules\Tenant\Application\Services\TenantContextService;

class ParentService implements ParentServiceInterface
{
    public function __construct(
        private readonly ParentTenantRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function list(ParentListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: ParentProfile::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['first_name', 'last_name', 'phone', 'email', 'relationship'],
            with: ['user', 'students'],
        );
    }

    public function find(int $id): ParentProfile
    {
        $parent = $this->repository->findForTenant(ParentProfile::class, $this->tenantId(), $id, ['user', 'students']);

        if ($parent === null) {
            throw (new ModelNotFoundException())->setModel(ParentProfile::class, [$id]);
        }

        return $parent;
    }

    public function create(ParentMutationData $data): ParentProfile
    {
        $attributes = $data->attributes;
        $provisionLogin = (bool) ($attributes['provision_login_account'] ?? false);
        unset($attributes['provision_login_account']);
        $attributes['tenant_id'] = $this->tenantId();

        /** @var ParentProfile $parent */
        $parent = $this->repository->create(ParentProfile::class, $attributes);

        Event::dispatch(new ParentCreated($parent->id, $parent->tenant_id, $provisionLogin));

        return $parent->refresh()->load(['user', 'students']);
    }

    public function update(int $id, ParentMutationData $data): ParentProfile
    {
        $parent = $this->find($id);
        $attributes = $data->attributes;
        unset($attributes['provision_login_account']);

        /** @var ParentProfile $updated */
        $updated = $this->repository->update($parent, $attributes);

        return $updated->refresh()->load(['user', 'students']);
    }

    public function delete(int $id): void
    {
        $parent = $this->find($id);
        $this->repository->delete($parent);
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
