<?php

namespace Modules\LMS\Listeners;

use Illuminate\Support\Facades\Log;

class LogLmsActivity
{
    public function handle(object $event): void
    {
        Log::info('LMS domain event handled.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
