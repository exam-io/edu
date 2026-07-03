<?php

namespace Modules\Enrollment\Listeners;

use Illuminate\Support\Facades\Log;

class LogUserManagementActivity
{
    public function handle(object $event): void
    {
        Log::info('Enrollment user management event dispatched.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
