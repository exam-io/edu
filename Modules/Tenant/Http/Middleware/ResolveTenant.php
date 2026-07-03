<?php

namespace Modules\Tenant\Http\Middleware;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Modules\Tenant\Application\Services\TenantResolverService;
use Modules\Tenant\Domain\Events\TenantResolved;
use App\Support\Tenancy\Contracts\TenantContextInterface;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __construct(
        private readonly TenantResolverService $tenantResolver,
        private readonly TenantContextInterface $tenantContext,
        private readonly Dispatcher $events,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->tenantResolver->resolve($request);

        if ($tenant === null) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant could not be resolved.',
            ], 404);
        }

        $this->tenantContext->setTenant($tenant);
        $request->attributes->set('tenant_id', $tenant->id);

        // Set permissions team ID for Spatie Permission multi-tenancy
        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($tenant->id);
        }

        $this->events->dispatch(new TenantResolved($tenant->id));

        return $next($request);
    }
}
