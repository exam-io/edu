<?php

namespace Modules\Notification\Infrastructure\Channels;

use Illuminate\Support\Facades\Log;
use Modules\Notification\Application\Contracts\NotificationChannelInterface;

class WebPushNotificationChannel implements NotificationChannelInterface
{
    public function send(array $payload): void
    {
        // Web Push package integration can be expanded with per-user subscriptions.
        Log::info('notification.web_push.dispatched', [
            'tenant_id' => $payload['tenant_id'] ?? null,
            'user_id' => $payload['user_id'] ?? null,
            'type' => $payload['notification_type'] ?? null,
        ]);
    }
}
