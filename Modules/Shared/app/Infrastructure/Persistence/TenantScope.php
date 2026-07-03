<?php

namespace Modules\Shared\Infrastructure\Persistence;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var TenantContextInterface $tenantContext */
        $tenantContext = app(TenantContextInterface::class);

        if (! $tenantContext->hasTenant()) {
            return;
        }

        $builder->where($model->getTable().'.tenant_id', $tenantContext->tenantId());
    }
}
