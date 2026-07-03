<?php

namespace Modules\Parent\Listeners;

use Modules\Parent\Application\Contracts\ParentProvisioningServiceInterface;
use Modules\Parent\Domain\Events\ParentCreated;
use Modules\Parent\Domain\Models\ParentProfile;

class CreateParentUserAccount
{
    public function __construct(private readonly ParentProvisioningServiceInterface $provisioningService)
    {
    }

    public function handle(ParentCreated $event): void
    {
        if (! $event->provisionLoginAccount) {
            return;
        }

        $parent = ParentProfile::query()->find($event->parentId);
        if ($parent === null) {
            return;
        }

        $this->provisioningService->provisionParentUser($parent);
    }
}
