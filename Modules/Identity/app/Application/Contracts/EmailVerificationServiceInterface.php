<?php

namespace Modules\Identity\Application\Contracts;

use App\Models\User;
use Modules\Identity\Application\DTOs\EmailVerificationData;

interface EmailVerificationServiceInterface
{
    public function verify(EmailVerificationData $data): bool;

    public function hasVerifiedEmail(User $user): bool;
}
