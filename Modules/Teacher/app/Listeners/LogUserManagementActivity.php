<?php

namespace Modules\Teacher\Listeners;

use Illuminate\Support\Facades\Log;

class LogUserManagementActivity
{
    public function handle(object $event): void
    {
        Log::info('Teacher user management event dispatched.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
