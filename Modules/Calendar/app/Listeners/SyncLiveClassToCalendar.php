<?php

namespace Modules\Calendar\Listeners;

use Modules\Calendar\Application\Contracts\CalendarEventServiceInterface;
use Modules\LiveClass\Domain\Events\LiveClassScheduled;
use Modules\LiveClass\Domain\Models\LiveClassSession;

class SyncLiveClassToCalendar
{
    public function __construct(
        private readonly CalendarEventServiceInterface $calendarService,
    ) {}

    public function handle(LiveClassScheduled $event): void
    {
        $liveClass = LiveClassSession::query()->find($event->liveClassId);

        if ($liveClass === null) {
            return;
        }

        $this->calendarService->upsertBySource($event->tenantId, 'live_class_session', $event->liveClassId, [
            'title' => $liveClass->title,
            'description' => $liveClass->description,
            'start_at' => $liveClass->scheduled_start_at,
            'end_at' => $liveClass->scheduled_end_at,
            'event_type' => 'live_class',
            'status' => $liveClass->status,
            'url' => $liveClass->meeting_url,
            'meta' => [
                'provider' => $liveClass->provider,
                'room_name' => $liveClass->room_name,
            ],
        ]);
    }
}
