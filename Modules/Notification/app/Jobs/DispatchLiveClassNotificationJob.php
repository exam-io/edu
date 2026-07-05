<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Enrollment\Domain\Models\StudentEnrollment;
use Modules\LiveClass\Domain\Models\LiveClassSession;
use Modules\Notification\Application\Contracts\NotificationServiceInterface;
use Modules\Notification\Application\DTOs\NotificationDispatchData;

class DispatchLiveClassNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $liveClassId,
        private readonly int $tenantId,
        private readonly string $eventType,
    ) {}

    public function handle(NotificationServiceInterface $notificationService): void
    {
        $liveClass = LiveClassSession::query()
            ->where('tenant_id', $this->tenantId)
            ->find($this->liveClassId);

        if ($liveClass === null) {
            return;
        }

        $recipientUserIds = collect([(int) $liveClass->host_user_id]);

        if ($liveClass->class_id !== null) {
            $studentUsers = StudentEnrollment::query()
                ->where('tenant_id', $this->tenantId)
                ->where('class_id', $liveClass->class_id)
                ->when($liveClass->section_id !== null, fn ($query) => $query->where('section_id', $liveClass->section_id))
                ->where('status', 'active')
                ->with('student:id,user_id')
                ->get()
                ->pluck('student.user_id')
                ->filter()
                ->map(static fn ($id): int => (int) $id);

            $recipientUserIds = $recipientUserIds->merge($studentUsers);
        }

        $title = match ($this->eventType) {
            'scheduled' => 'Live class scheduled',
            'started' => 'Live class started',
            'ended' => 'Live class ended',
            default => 'Live class update',
        };

        $notificationService->dispatch(new NotificationDispatchData(
            tenantId: $this->tenantId,
            type: 'live_class.' . $this->eventType,
            title: $title,
            body: $liveClass->title,
            targetUserIds: $recipientUserIds->unique()->values()->all(),
            channels: ['in_app', 'fcm', 'web_push'],
            data: [
                'live_class_id' => $liveClass->id,
                'meeting_url' => $liveClass->meeting_url,
                'status' => $liveClass->status,
            ],
        ));
    }
}
