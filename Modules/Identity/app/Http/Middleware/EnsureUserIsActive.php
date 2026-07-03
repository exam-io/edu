<?php

namespace Modules\Identity\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Identity\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return ApiResponse::error('Unauthenticated.', status: 401);
        }

        if (($user->status ?? null) !== 'active') {
            return ApiResponse::error('User account is not active.', status: 403);
        }

        return $next($request);
    }
}
