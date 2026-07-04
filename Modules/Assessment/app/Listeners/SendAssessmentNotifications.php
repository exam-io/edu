<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendAssessmentNotifications implements ShouldQueue
{
    public function handle(object $event): void
    {
        $tenantId = property_exists($event, 'tenantId') && is_int($event->tenantId) ? $event->tenantId : null;
        if ($tenantId !== null) {
            $feedKey = "assessment:notifications:tenant:{$tenantId}:feed";
            $feed = Cache::get($feedKey, []);

            if (! is_array($feed)) {
                $feed = [];
            }

            array_unshift($feed, [
                'event' => class_basename($event),
                'tenant_id' => $tenantId,
                'assessment_id' => property_exists($event, 'assessmentId') ? $event->assessmentId : null,
                'student_id' => property_exists($event, 'studentId') ? $event->studentId : null,
                'attempt_id' => property_exists($event, 'attemptId') ? $event->attemptId : null,
                'at' => now()->toIso8601String(),
            ]);

            $feed = array_slice($feed, 0, 50);
            Cache::put($feedKey, $feed, now()->addDays(7));
        }

        Log::info('Assessment notification dispatch requested.', [
            'event' => $event::class,
            'tenant_id' => $tenantId,
        ]);
    }
}
