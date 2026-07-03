<?php

namespace Modules\Identity\Application\Services;

use App\Models\User;
use Modules\Identity\Application\Contracts\AuthenticationServiceInterface;
use Modules\Identity\Application\Contracts\CurrentUserServiceInterface;
use Modules\Identity\Application\DTOs\LoginData;
use Modules\Identity\Application\Interfaces\AuthenticatorInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly CurrentUserServiceInterface $currentUserService,
    ) {
    }

    public function authenticate(LoginData $data): ?User
    {
        $credentials = [
            'email' => $data->email,
            'password' => $data->password,
            'status' => 'active',
        ];

        if ($data->tenantId !== null) {
            $credentials['tenant_id'] = $data->tenantId;
        }

        if (! $this->authenticator->attempt($credentials, $data->remember)) {
            return null;
        }

        $user = $this->currentUserService->user();

        if ($user === null) {
            return null;
        }

        return $user;
    }

    public function logout(): void
    {
        $this->authenticator->logout();
    }
}
