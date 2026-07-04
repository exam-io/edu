<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendAssessmentNotifications implements ShouldQueue
{
    public function handle(object $event): void
    {
        Log::info('Assessment notification dispatch requested.', [
            'event' => $event::class,
        ]);
    }
}
