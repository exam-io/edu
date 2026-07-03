<?php

namespace Modules\Identity\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Identity\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return ApiResponse::error('Unauthenticated.', status: 401);
        }

        if ($user->email_verified_at === null) {
            return ApiResponse::error('Email address is not verified.', status: 403);
        }

        return $next($request);
    }
}
