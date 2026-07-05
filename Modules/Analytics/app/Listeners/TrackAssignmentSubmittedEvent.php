<?php

namespace Modules\Analytics\Listeners;

use Modules\Analytics\Application\Contracts\AnalyticsServiceInterface;
use Modules\Analytics\Application\DTOs\TrackEventData;
use Modules\Assignment\Domain\Events\AssignmentSubmitted;

class TrackAssignmentSubmittedEvent
{
    public function __construct(
        private readonly AnalyticsServiceInterface $analytics,
    ) {}

    public function handle(AssignmentSubmitted $event): void
    {
        $this->analytics->track(TrackEventData::fromArray([
            'event_name' => 'assignment.submitted',
            'source_module' => 'Assignment',
            'entity_type' => 'assignment_submission',
            'entity_id' => $event->submissionId,
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
