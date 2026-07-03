<?php

namespace Modules\Institute\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Institute\Domain\Events\InstituteRegistered;

class SendWelcomeEmail
{
    public function handle(InstituteRegistered $event): void
    {
        // Messaging is intentionally decoupled until institute-specific mail templates are finalized.
        Log::info('Institute welcome notification queued.', [
            'institute_id' => $event->instituteId,
            'actor_user_id' => $event->actorUserId,
        ]);
    }
}
