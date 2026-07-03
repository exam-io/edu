<?php

namespace App\Http\Middleware;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use App\Support\Tenancy\Contracts\TenantResolverInterface;
use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Modules\Tenant\Domain\Events\TenantResolved;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __construct(
        private readonly TenantResolverInterface $tenantResolver,
        private readonly TenantContextInterface $tenantContext,
        private readonly Dispatcher $events,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->tenantResolver->resolve($request->getHost());
        $this->tenantContext->setTenant($tenant);

        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($tenant?->id);
        }

        if ($tenant !== null) {
            $request->attributes->set('tenant_id', $tenant->id);
            $this->events->dispatch(new TenantResolved($tenant->id));
        }

        return $next($request);
    }
}
