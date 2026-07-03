<?php

namespace Modules\Tenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenant\Application\Services\TenantContextService;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function __construct(
        private readonly TenantContextService $contextService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
