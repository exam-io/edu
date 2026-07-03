<?php

namespace Modules\Course\Listeners;

use Illuminate\Support\Facades\Log;

class LogCourseActivity
{
    public function handle(object $event): void
    {
        Log::info('Course domain event handled.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
