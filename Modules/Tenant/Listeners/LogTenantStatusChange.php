<?php

namespace Modules\Tenant\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Tenant\Domain\Events\TenantActivated;
use Modules\Tenant\Domain\Events\TenantSuspended;

class LogTenantStatusChange
{
    public function handleActivated(TenantActivated $event): void
    {
        Log::info('Tenant activated', ['tenant_id' => $event->tenantId]);
    }

    public function handleSuspended(TenantSuspended $event): void
    {
        Log::info('Tenant suspended', ['tenant_id' => $event->tenantId]);
    }
}
