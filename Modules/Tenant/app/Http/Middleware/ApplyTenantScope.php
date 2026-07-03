<?php

namespace Modules\Tenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenant\Application\Services\TenantContextService;
use Symfony\Component\HttpFoundation\Response;

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

        return $next($request);
    }
}
