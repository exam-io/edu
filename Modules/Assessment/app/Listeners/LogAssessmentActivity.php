<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Support\Facades\Log;

class LogAssessmentActivity
{
    public function handle(object $event): void
    {
        $context = [
            'event' => $event::class,
        ];

        foreach (['tenantId', 'assessmentId', 'studentId', 'attemptId', 'requestId', 'outputId'] as $property) {
            if (property_exists($event, $property)) {
                $context[$property] = $event->{$property};
            }
        }

        Log::info('Assessment activity event.', [
            'context' => $context,
        ]);
    }
}
