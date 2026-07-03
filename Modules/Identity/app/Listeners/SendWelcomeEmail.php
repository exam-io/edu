<?php

namespace Modules\Identity\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Identity\Events\UserRegistered;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event): void
    {
        // Step 2 foundation: keep email side-effect observable without coupling to a mail template.
        Log::info('Welcome email queued.', [
            'user_id' => $event->userId,
            'tenant_id' => $event->tenantId,
            'email' => $event->email,
        ]);
    }
}
