<?php

namespace Modules\Identity\Application\Services;

use Modules\Identity\Application\Contracts\PasswordResetServiceInterface;
use Modules\Identity\Application\DTOs\PasswordResetData;
use Modules\Identity\Application\DTOs\PasswordResetLinkData;
use Modules\Identity\Application\Interfaces\PasswordResetBrokerInterface;

class PasswordResetService implements PasswordResetServiceInterface
{
    public function __construct(private readonly PasswordResetBrokerInterface $broker)
    {
    }

    public function sendResetLink(PasswordResetLinkData $data): string
    {
        return $this->broker->sendResetLink($data->email);
    }

    public function resetPassword(PasswordResetData $data): string
    {
        return $this->broker->reset($data->email, $data->token, $data->password);
    }
}
