<?php

namespace Modules\Notification\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Notification\Domain\Models\NotificationDeviceToken;
use Modules\Notification\Domain\Models\TenantNotification;

interface NotificationRepositoryInterface
{
    public function paginateForUser(int $tenantId, int $userId, int $perPage, bool $onlyUnread = false): LengthAwarePaginator;

    public function create(array $attributes): TenantNotification;

    public function markRead(int $tenantId, int $userId, int $notificationId): ?TenantNotification;

    public function unreadCount(int $tenantId, int $userId): int;

    public function upsertDeviceToken(int $tenantId, int $userId, string $token, array $attributes = []): NotificationDeviceToken;

    /**
     * @return list<string>
     */
    public function activeDeviceTokens(int $tenantId, int $userId): array;
}
