<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LiveClass\Domain\Events\LiveClassEnded;
use Modules\LiveClass\Domain\Events\LiveClassScheduled;
use Modules\LiveClass\Domain\Events\LiveClassStarted;
use Modules\Notification\Jobs\DispatchLiveClassNotificationJob;

class QueueLiveClassNotifications implements ShouldQueue
{
    public function handle(LiveClassScheduled|LiveClassStarted|LiveClassEnded $event): void
    {
        $eventType = match (true) {
            $event instanceof LiveClassScheduled => 'scheduled',
            $event instanceof LiveClassStarted => 'started',
            $event instanceof LiveClassEnded => 'ended',
        };

        DispatchLiveClassNotificationJob::dispatch($event->liveClassId, $event->tenantId, $eventType);
    }
}
