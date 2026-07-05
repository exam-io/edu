<?php

namespace Modules\Analytics\Listeners;

use Modules\Analytics\Application\Contracts\AnalyticsServiceInterface;
use Modules\Analytics\Application\DTOs\TrackEventData;
use Modules\Assessment\Domain\Events\AssessmentSubmitted;

class TrackAssessmentSubmittedEvent
{
    public function __construct(
        private readonly AnalyticsServiceInterface $analytics,
    ) {}

    public function handle(AssessmentSubmitted $event): void
    {
        $this->analytics->track(TrackEventData::fromArray([
            'event_name' => 'assessment.submitted',
            'source_module' => 'Assessment',
            'entity_type' => 'assessment_attempt',
            'entity_id' => $event->attemptId,
            'payload' => [
                'assessment_id' => $event->assessmentId,
                'student_id' => $event->studentId,
                'tenant_id' => $event->tenantId,
            ],
            'occurred_at' => now()->toIso8601String(),
            'tenant_id' => $event->tenantId,
        ]));
    }
}
