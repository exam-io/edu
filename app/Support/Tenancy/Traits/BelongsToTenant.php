<?php

namespace App\Support\Tenancy\Traits;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Trait for tenant-aware models.
 * Automatically scopes queries to the current tenant.
 * 
 * Usage:
 *   class User extends Model {
 *       use BelongsToTenant;
 *   }
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    public function getTenantIdColumn(): string
    {
        return 'tenant_id';
    }
}

/**
 * Global scope for automatic tenant scoping.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantContext = app(TenantContextInterface::class);
        $tenantId = $tenantContext->tenantId();

        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}
