<?php

namespace Modules\Tenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenant\Application\Services\TenantContextService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to apply tenant scope and verify context.
 * Ensures all queries in this request are scoped to the current tenant.
 * Can be bypassed for superadmin routes by checking user roles.
 */
class ApplyTenantScope
{
    public function __construct(
        private readonly TenantContextService $contextService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->contextService->tenant();

        if ($tenant === null) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not established.',
            ], 400);
        }

        // Tenant scope is automatically applied via BelongsToTenant trait
        // This middleware is mainly a guard to verify context exists

        return $next($request);
    }
}
