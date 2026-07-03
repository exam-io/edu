<?php

namespace Modules\Identity\Application\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Modules\Identity\Application\Contracts\CurrentUserServiceInterface;
use Modules\Identity\Application\Interfaces\AuthenticatorInterface;

class CurrentUserService implements CurrentUserServiceInterface
{
    public function __construct(private readonly AuthenticatorInterface $authenticator)
    {
    }

    public function user(): ?User
    {
        $user = $this->authenticator->user();

        return $user instanceof User ? $user : null;
    }

    public function userOrFail(): User
    {
        $user = $this->user();

        if ($user === null) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return $user;
    }
}
