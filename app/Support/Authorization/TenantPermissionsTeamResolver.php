<?php

namespace App\Support\Authorization;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Contracts\PermissionsTeamResolver;
use Throwable;

class TenantPermissionsTeamResolver implements PermissionsTeamResolver
{
    protected int|string|null $teamId = null;

    public function getPermissionsTeamId(): int|string|null
    {
        if ($this->teamId !== null) {
            return $this->teamId;
        }

        try {
            /** @var TenantContextInterface $tenantContext */
            $tenantContext = app(TenantContextInterface::class);

            return $tenantContext->tenantId();
        } catch (Throwable) {
            return null;
        }
    }

    public function setPermissionsTeamId(int|string|Model|null $id): void
    {
        if ($id instanceof Model) {
            $id = $id->getKey();
        }

        $this->teamId = $id;
    }
}
