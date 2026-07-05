<?php

namespace Modules\MobileProvisioning\Application\DTOs;

readonly class MobileConfigMutationData
{
    public function __construct(
        public array $overrides,
        public ?string $minAppVersion,
        public ?string $supportEmail,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            overrides: isset($payload['overrides']) && is_array($payload['overrides']) ? $payload['overrides'] : [],
            minAppVersion: isset($payload['min_app_version']) ? (string) $payload['min_app_version'] : null,
            supportEmail: isset($payload['support_email']) ? (string) $payload['support_email'] : null,
        );
    }
}
