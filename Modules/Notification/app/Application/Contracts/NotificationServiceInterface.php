<?php

namespace Modules\Notification\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Notification\Application\DTOs\DeviceTokenData;
use Modules\Notification\Application\DTOs\NotificationDispatchData;
use Modules\Notification\Domain\Models\NotificationDeviceToken;
use Modules\Notification\Domain\Models\TenantNotification;

interface NotificationServiceInterface
{
    public function inbox(int $perPage = 20, bool $onlyUnread = false): LengthAwarePaginator;

    public function markRead(int $id): TenantNotification;

    public function unreadCount(): int;

    public function registerDeviceToken(DeviceTokenData $data): NotificationDeviceToken;

    public function dispatch(NotificationDispatchData $data): void;
}
