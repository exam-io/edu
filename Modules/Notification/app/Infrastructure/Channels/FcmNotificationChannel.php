<?php

namespace Modules\Notification\Infrastructure\Channels;

use Illuminate\Support\Facades\Log;
use Modules\Notification\Application\Contracts\NotificationChannelInterface;

class FcmNotificationChannel implements NotificationChannelInterface
{
    public function send(array $payload): void
    {
        // FCM package integration can be expanded with project credentials.
        Log::info('notification.fcm.dispatched', [
            'tenant_id' => $payload['tenant_id'] ?? null,
            'user_id' => $payload['user_id'] ?? null,
            'type' => $payload['notification_type'] ?? null,
        ]);
    }
}
