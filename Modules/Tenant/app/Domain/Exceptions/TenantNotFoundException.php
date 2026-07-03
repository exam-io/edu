<?php

namespace Modules\Tenant\Domain\Exceptions;

class TenantNotFoundException extends \RuntimeException
{
    public static function byId(int $id): self
    {
        return new self("Tenant with ID {$id} not found.");
    }

    public static function bySlug(string $slug): self
    {
        return new self("Tenant with slug '{$slug}' not found.");
    }

    public static function byDomain(string $domain): self
    {
        return new self("Tenant with domain '{$domain}' not found.");
    }
}
