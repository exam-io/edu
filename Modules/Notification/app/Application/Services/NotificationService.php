<?php

namespace Modules\Notification\Application\Services;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Application\Contracts\NotificationChannelInterface;
use Modules\Notification\Application\Contracts\NotificationRepositoryInterface;
use Modules\Notification\Application\Contracts\NotificationServiceInterface;
use Modules\Notification\Application\DTOs\DeviceTokenData;
use Modules\Notification\Application\DTOs\NotificationDispatchData;
use Modules\Notification\Domain\Models\NotificationDeviceToken;
use Modules\Notification\Domain\Models\TenantNotification;

class NotificationService implements NotificationServiceInterface
{
    /**
     * @param array<string, NotificationChannelInterface> $channels
     */
    public function __construct(
        private readonly NotificationRepositoryInterface $repository,
        private readonly array $channels,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function inbox(int $perPage = 20, bool $onlyUnread = false): LengthAwarePaginator
    {
        return $this->repository->paginateForUser($this->tenantId(), (int) Auth::id(), $perPage, $onlyUnread);
    }

    public function markRead(int $id): TenantNotification
    {
        $notification = $this->repository->markRead($this->tenantId(), (int) Auth::id(), $id);

        abort_if($notification === null, 404, 'Notification not found.');

        return $notification;
    }

    public function unreadCount(): int
    {
        return $this->repository->unreadCount($this->tenantId(), (int) Auth::id());
    }

    public function registerDeviceToken(DeviceTokenData $data): NotificationDeviceToken
    {
        return $this->repository->upsertDeviceToken($this->tenantId(), (int) Auth::id(), $data->token, [
            'device_type' => $data->deviceType,
            'is_active' => $data->isActive,
            'meta' => $data->meta,
            'last_seen_at' => now(),
        ]);
    }

    public function dispatch(NotificationDispatchData $data): void
    {
        foreach ($data->targetUserIds as $userId) {
            $notification = $this->repository->create([
                'tenant_id' => $data->tenantId,
                'user_id' => $userId,
                'notification_type' => $data->type,
                'title' => $data->title,
                'body' => $data->body,
                'status' => 'sent',
                'channels' => $data->channels,
                'data' => $data->data,
                'sent_at' => now(),
            ]);

            foreach ($data->channels as $channelName) {
                $channel = $this->channels[$channelName] ?? null;
                if ($channel === null || $channelName === 'in_app') {
                    continue;
                }

                $channel->send([
                    'tenant_id' => $notification->tenant_id,
                    'user_id' => $notification->user_id,
                    'notification_type' => $notification->notification_type,
                    'title' => $notification->title,
                    'body' => $notification->body,
                    'data' => $notification->data,
                    'tokens' => $this->repository->activeDeviceTokens($data->tenantId, $userId),
                ]);
            }
        }
    }

    private function tenantId(): int
    {
        $tenantId = $this->tenantContext->tenantId();
        abort_if($tenantId === null, 422, 'Tenant context is required.');

        return (int) $tenantId;
    }
}
