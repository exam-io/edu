<?php

namespace Modules\Tenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenant\Application\Services\TenantContextService;
use Modules\Tenant\Domain\Exceptions\TenantNotActiveException;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantActive
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
                'message' => 'Tenant not found.',
            ], 404);
        }

        if (! $tenant->isActive()) {
            return response()->json([
                'success' => false,
                'message' => TenantNotActiveException::forStatus($tenant->status->value)->getMessage(),
            ], 503);
        }

        return $next($request);
    }
}
