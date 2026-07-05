<?php

namespace Modules\Notification\Application\Contracts;

interface NotificationChannelInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function send(array $payload): void;
}
