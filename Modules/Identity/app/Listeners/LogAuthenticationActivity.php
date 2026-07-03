<?php

namespace Modules\Identity\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Identity\Events\EmailVerified;
use Modules\Identity\Events\PasswordResetRequested;
use Modules\Identity\Events\UserLoggedIn;
use Modules\Identity\Events\UserLoggedOut;
use Modules\Identity\Events\UserRegistered;

class LogAuthenticationActivity
{
    public function handle(UserRegistered|UserLoggedIn|UserLoggedOut|PasswordResetRequested|EmailVerified $event): void
    {
        if ($event instanceof UserRegistered) {
            Log::info('Authentication activity: user registered.', [
                'user_id' => $event->userId,
                'tenant_id' => $event->tenantId,
                'email' => $event->email,
            ]);

            return;
        }

        if ($event instanceof UserLoggedIn) {
            Log::info('Authentication activity: user logged in.', [
                'user_id' => $event->userId,
                'tenant_id' => $event->tenantId,
                'ip_address' => $event->ipAddress,
            ]);

            return;
        }

        if ($event instanceof UserLoggedOut) {
            Log::info('Authentication activity: user logged out.', [
                'user_id' => $event->userId,
                'tenant_id' => $event->tenantId,
            ]);

            return;
        }

        if ($event instanceof PasswordResetRequested) {
            Log::info('Authentication activity: password reset requested.', [
                'email' => $event->email,
                'tenant_id' => $event->tenantId,
            ]);

            return;
        }

        Log::info('Authentication activity: email verified.', [
            'user_id' => $event->userId,
            'tenant_id' => $event->tenantId,
        ]);
    }
}
