<?php

namespace Modules\Parent\Listeners;

use Illuminate\Support\Facades\Log;

class SendWelcomeNotification
{
    public function handle(object $event): void
    {
        Log::info('Welcome notification dispatched for parent event.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
