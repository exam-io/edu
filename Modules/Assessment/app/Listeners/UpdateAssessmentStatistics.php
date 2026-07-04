<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Modules\Assessment\Domain\Events\AssessmentCreated;
use Modules\Assessment\Domain\Events\AssessmentPublished;
use Modules\Assessment\Domain\Events\AssessmentStarted;
use Modules\Assessment\Domain\Events\AssessmentSubmitted;

class UpdateAssessmentStatistics implements ShouldQueue
{
    public function handle(object $event): void
    {
        $tenantId = $this->readInt($event, 'tenantId');
        if ($tenantId === null) {
            return;
        }

        $day = now()->format('Y-m-d');
        $eventKey = strtolower(class_basename($event));

        $this->incrementWithTtl("assessment:stats:tenant:{$tenantId}:{$day}:events_total");
        $this->incrementWithTtl("assessment:stats:tenant:{$tenantId}:{$day}:event:{$eventKey}");

        if ($event instanceof AssessmentCreated) {
            $this->incrementWithTtl("assessment:stats:tenant:{$tenantId}:{$day}:created");
        }

        if ($event instanceof AssessmentPublished) {
            $this->incrementWithTtl("assessment:stats:tenant:{$tenantId}:{$day}:published");
        }

        if ($event instanceof AssessmentStarted) {
            $this->incrementWithTtl("assessment:stats:tenant:{$tenantId}:{$day}:attempts_started");
        }

        if ($event instanceof AssessmentSubmitted) {
            $this->incrementWithTtl("assessment:stats:tenant:{$tenantId}:{$day}:attempts_submitted");
        }
    }

    private function incrementWithTtl(string $key): void
    {
        Cache::add($key, 0, now()->addDays(30));
        Cache::increment($key);
    }

    private function readInt(object $event, string $property): ?int
    {
        if (! property_exists($event, $property)) {
            return null;
        }

        $value = $event->{$property};

        return is_int($value) ? $value : null;
    }
}
