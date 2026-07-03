<?php

namespace Modules\Identity\Http\Middleware;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Closure;
use Illuminate\Http\Request;
use Modules\Identity\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantResolved
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->tenantContext->hasTenant()) {
            return ApiResponse::error('Tenant could not be resolved for this request.', status: 422);
        }

        return $next($request);
    }
}
