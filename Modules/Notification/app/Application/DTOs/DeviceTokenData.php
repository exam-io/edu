<?php

namespace Modules\Notification\Application\DTOs;

readonly class DeviceTokenData
{
    /**
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public string $token,
        public string $deviceType = 'web',
        public bool $isActive = true,
        public array $meta = [],
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            token: (string) $payload['token'],
            deviceType: (string) ($payload['device_type'] ?? 'web'),
            isActive: (bool) ($payload['is_active'] ?? true),
            meta: isset($payload['meta']) && is_array($payload['meta']) ? $payload['meta'] : [],
        );
    }
}
