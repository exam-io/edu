<?php

namespace Modules\Parent\Application\Contracts;

use Modules\Parent\Domain\Models\ParentProfile;

interface ParentProvisioningServiceInterface
{
    public function provisionParentUser(ParentProfile $parent): void;
}
