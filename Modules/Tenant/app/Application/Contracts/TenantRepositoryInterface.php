<?php

namespace Modules\Tenant\Application\Contracts;

use Illuminate\Support\Collection;
use Modules\Tenant\Domain\Models\Tenant;

interface TenantRepositoryInterface
{
    public function findById(int $id): ?Tenant;

    public function findBySlug(string $slug): ?Tenant;

    public function findByDomain(string $domain): ?Tenant;

    public function findByCustomDomain(string $domain): ?Tenant;

    public function allActive(): Collection;

    public function all(): Collection;
}
