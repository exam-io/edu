<?php

namespace Modules\System\Application\DTOs;

readonly class SecurityPolicyData
{
    public function __construct(
        public bool $forceMfa,
        public int $sessionTtlMinutes,
        public int $passwordRotationDays,
        public bool $allowIpRestriction,
        public array $allowedIpRanges,
        public bool $strictTransportSecurity,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            forceMfa: (bool) ($input['force_mfa'] ?? false),
            sessionTtlMinutes: (int) ($input['session_ttl_minutes'] ?? 120),
            passwordRotationDays: (int) ($input['password_rotation_days'] ?? 90),
            allowIpRestriction: (bool) ($input['allow_ip_restriction'] ?? false),
            allowedIpRanges: is_array($input['allowed_ip_ranges'] ?? null) ? $input['allowed_ip_ranges'] : [],
            strictTransportSecurity: (bool) ($input['strict_transport_security'] ?? true),
        );
    }
}
