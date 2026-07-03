<?php

namespace Modules\Identity\Application\Services;

use App\Models\User;
use Modules\Identity\Application\Contracts\EmailVerificationServiceInterface;
use Modules\Identity\Application\DTOs\EmailVerificationData;

class EmailVerificationService implements EmailVerificationServiceInterface
{
    public function verify(EmailVerificationData $data): bool
    {
        $user = User::query()->find($data->userId);

        if (! $user instanceof User) {
            return false;
        }

        if ($data->tenantId !== null && (int) $user->tenant_id !== $data->tenantId) {
            return false;
        }

        if (sha1($user->email) !== $data->emailHash) {
            return false;
        }

        if ($this->hasVerifiedEmail($user)) {
            return true;
        }

        $user->forceFill(['email_verified_at' => now()])->save();

        return true;
    }

    public function hasVerifiedEmail(User $user): bool
    {
        return $user->email_verified_at !== null;
    }
}
