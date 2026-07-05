<?php

namespace Modules\Notification\Application\DTOs;

readonly class NotificationDispatchData
{
    /**
     * @param list<int> $targetUserIds
     * @param list<string> $channels
     * @param array<string, mixed> $data
     */
    public function __construct(
        public int $tenantId,
        public string $type,
        public string $title,
        public ?string $body,
        public array $targetUserIds,
        public array $channels = ['in_app'],
        public array $data = [],
    ) {}
}
