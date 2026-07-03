<?php

namespace Modules\Academic\Listeners;

use Illuminate\Support\Facades\Log;

class LogAcademicActivity
{
    public function handle(object $event): void
    {
        Log::info('Academic event dispatched.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
