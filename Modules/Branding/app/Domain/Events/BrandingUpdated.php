<?php

namespace Modules\Branding\Domain\Events;

readonly class BrandingUpdated
{
    public function __construct(
        public int $tenantId,
    ) {}
}
