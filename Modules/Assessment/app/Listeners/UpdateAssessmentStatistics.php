<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdateAssessmentStatistics implements ShouldQueue
{
    public function handle(object $event): void
    {
        Log::info('Assessment statistics update requested.', [
            'event' => $event::class,
        ]);
    }
}
