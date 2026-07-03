<?php

namespace Modules\Identity\Http\Middleware;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Closure;
use Illuminate\Http\Request;
use Modules\Identity\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if ($user === null) {
            return ApiResponse::error('Unauthenticated.', status: 401);
        }

        if ($this->tenantContext->hasTenant() && function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($this->tenantContext->tenantId());
        }

        if ($permissions === []) {
            return $next($request);
        }

        if (! $user->canAny($permissions)) {
            return ApiResponse::error('Insufficient permissions.', status: 403);
        }

        return $next($request);
    }
}
