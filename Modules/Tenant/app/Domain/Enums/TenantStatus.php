<?php

namespace Modules\Tenant\Domain\Enums;

enum TenantStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case INACTIVE = 'inactive';
    case PROVISIONING = 'provisioning';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function canBeActivated(): bool
    {
        return $this !== self::ACTIVE;
    }
}
