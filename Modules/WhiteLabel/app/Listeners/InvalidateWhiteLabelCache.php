<?php

namespace Modules\WhiteLabel\Listeners;

use Modules\WhiteLabel\Application\Contracts\NavigationServiceInterface;

class InvalidateWhiteLabelCache
{
    public function __construct(
        private readonly NavigationServiceInterface $navigationService,
    ) {}

    public function handle(object $event): void
    {
        $tenantId = (int) ($event->tenantId ?? 0);

        if ($tenantId > 0) {
            $this->navigationService->invalidateCache($tenantId);
        }
    }
}
