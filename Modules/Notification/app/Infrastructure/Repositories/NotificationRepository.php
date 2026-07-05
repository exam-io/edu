<?php

namespace Modules\Notification\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Notification\Application\Contracts\NotificationRepositoryInterface;
use Modules\Notification\Domain\Models\NotificationDeviceToken;
use Modules\Notification\Domain\Models\TenantNotification;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function paginateForUser(int $tenantId, int $userId, int $perPage, bool $onlyUnread = false): LengthAwarePaginator
    {
        $builder = TenantNotification::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->latest();

        if ($onlyUnread) {
            $builder->whereNull('read_at');
        }

        return $builder->paginate($perPage);
    }

    public function create(array $attributes): TenantNotification
    {
        return TenantNotification::query()->create($attributes);
    }

    public function markRead(int $tenantId, int $userId, int $notificationId): ?TenantNotification
    {
        $notification = TenantNotification::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->find($notificationId);

        if ($notification === null) {
            return null;
        }

        if ($notification->read_at === null) {
            $notification->forceFill([
                'read_at' => now(),
                'status' => 'read',
            ])->save();
        }

        return $notification->refresh();
    }

    public function unreadCount(int $tenantId, int $userId): int
    {
        return TenantNotification::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function upsertDeviceToken(int $tenantId, int $userId, string $token, array $attributes = []): NotificationDeviceToken
    {
        return NotificationDeviceToken::query()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'token' => $token,
            ],
            array_merge($attributes, ['user_id' => $userId]),
        );
    }

    public function activeDeviceTokens(int $tenantId, int $userId): array
    {
        return NotificationDeviceToken::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('token')
            ->all();
    }
}
