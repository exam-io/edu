<?php

namespace Modules\Tenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenant\Application\Services\TenantContextService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to set tenant context for the request.
 * Runs after ResolveTenant; ensures tenant is set for all downstream code.
 */
class SetTenantContext
{
    public function __construct(
        private readonly TenantContextService $contextService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Tenant is already resolved by ResolveTenant middleware
        // This middleware is a no-op in most cases, but can be used for 
        // context binding and teardown if needed in the future

        return $next($request);
    }
}
