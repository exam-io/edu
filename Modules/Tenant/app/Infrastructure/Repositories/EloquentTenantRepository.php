<?php

namespace Modules\Tenant\Infrastructure\Repositories;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Domain\Models\Tenant;

class EloquentTenantRepository implements TenantRepositoryInterface
{
    public function findById(int $id): ?Tenant
    {
        return Tenant::find($id);
    }

    public function findBySlug(string $slug): ?Tenant
    {
        return Tenant::query()->where('slug', $slug)->first();
    }

    public function findByDomain(string $domain): ?Tenant
    {
        return Tenant::query()->where('domain', $domain)->first();
    }

    public function findByCustomDomain(string $domain): ?Tenant
    {
        if (! Schema::hasColumn('tenants', 'custom_domain')) {
            return null;
        }

        return Tenant::query()->where('custom_domain', $domain)->first();
    }

    public function allActive(): Collection
    {
        return Tenant::query()->where('status', 'active')->get();
    }

    public function all(): Collection
    {
        return Tenant::all();
    }
}
