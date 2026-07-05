<?php

namespace Modules\WhiteLabel\Application\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\WhiteLabel\Application\Contracts\DomainServiceInterface;
use Modules\WhiteLabel\Application\DTOs\DomainMutationData;
use Modules\WhiteLabel\Domain\Events\DomainMapped;
use Modules\WhiteLabel\Domain\Models\TenantDomain;

class DomainService implements DomainServiceInterface
{
    public function list(int $tenantId): Collection
    {
        return TenantDomain::query()
            ->where('tenant_id', $tenantId)
            ->orderByDesc('is_primary')
            ->orderBy('host')
            ->get();
    }

    public function create(int $tenantId, DomainMutationData $data): TenantDomain
    {
        if ($data->isPrimary) {
            TenantDomain::query()->where('tenant_id', $tenantId)->update(['is_primary' => false]);
        }

        $domain = TenantDomain::query()->create([
            'tenant_id' => $tenantId,
            'host' => $data->host,
            'is_primary' => $data->isPrimary,
            'status' => $data->status ?? 'pending_verification',
            'verification_token' => Str::random(32),
        ]);

        event(new DomainMapped($tenantId));

        return $domain;
    }

    public function update(int $tenantId, int $id, DomainMutationData $data): TenantDomain
    {
        $domain = TenantDomain::query()->where('tenant_id', $tenantId)->whereKey($id)->firstOrFail();

        if ($data->isPrimary) {
            TenantDomain::query()
                ->where('tenant_id', $tenantId)
                ->where('id', '!=', $domain->id)
                ->update(['is_primary' => false]);
        }

        $domain->fill([
            'host' => $data->host,
            'is_primary' => $data->isPrimary,
            'status' => $data->status ?? $domain->status,
        ]);
        $domain->save();

        event(new DomainMapped($tenantId));

        return $domain->refresh();
    }
}
