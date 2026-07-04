<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Support\Facades\Log;

class LogAssessmentActivity
{
    public function handle(object $event): void
    {
        Log::info('Assessment activity event.', [
            'event' => $event::class,
        ]);
    }
}
