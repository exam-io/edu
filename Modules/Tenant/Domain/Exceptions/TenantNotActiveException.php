<?php

namespace Modules\Tenant\Domain\Exceptions;

class TenantNotActiveException extends \RuntimeException
{
    public static function forStatus(string $status): self
    {
        return new self("Tenant is not active. Current status: {$status}");
    }

    public static function suspended(): self
    {
        return new self("Tenant is suspended.");
    }

    public static function provisioning(): self
    {
        return new self("Tenant is currently provisioning. Please try again later.");
    }
}
