<?php

namespace Modules\Tenant\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Domain\Models\Tenant;

class EloquentTenantRepository implements TenantRepositoryInterface
{
    public function findByDomain(string $domain): ?Tenant
    {
        return Tenant::query()->where('domain', $domain)->first();
    }

    public function allActive(): Collection
    {
        return Tenant::query()->where('status', 'active')->get();
    }
}
