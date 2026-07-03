<?php

namespace Modules\Identity\Application\Contracts;

use Modules\Identity\Application\DTOs\PasswordResetData;
use Modules\Identity\Application\DTOs\PasswordResetLinkData;

interface PasswordResetServiceInterface
{
    public function sendResetLink(PasswordResetLinkData $data): string;

    public function resetPassword(PasswordResetData $data): string;
}
