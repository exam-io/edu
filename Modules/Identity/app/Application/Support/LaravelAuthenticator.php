<?php

namespace Modules\Identity\Application\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Modules\Identity\Application\Interfaces\AuthenticatorInterface;
use Throwable;

class LaravelAuthenticator implements AuthenticatorInterface
{
    public function attempt(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    public function logout(): void
    {
        try {
            $guard = Auth::guard();

            if (method_exists($guard, 'logout')) {
                $guard->logout();
            }
        } catch (Throwable) {
            // In stateless token flows, some guards may not support or require logout.
        }
    }

    public function user(): ?Authenticatable
    {
        return Auth::user();
    }
}
