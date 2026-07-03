<?php

namespace Modules\Shared\Domain\Models;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Database\Eloquent\Model;
use Modules\Shared\Infrastructure\Persistence\TenantScope;

abstract class TenantAwareModel extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (Model $model): void {
            if ($model->getAttribute('tenant_id') !== null) {
                return;
            }

            /** @var TenantContextInterface $tenantContext */
            $tenantContext = app(TenantContextInterface::class);
            $tenantId = $tenantContext->tenantId();

            if ($tenantId !== null) {
                $model->setAttribute('tenant_id', $tenantId);
            }
        });
    }
}
