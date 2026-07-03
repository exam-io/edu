<?php

namespace Modules\Shared\Http\Middleware;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->tenantContext->hasTenant()) {
            return response()->json(['message' => 'Tenant context is required.'], 400);
        }

        return $next($request);
    }
}
