<?php

namespace Modules\Tenant\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Tenant\Domain\Events\TenantResolved;

class LogTenantResolved
{
    public function handle(TenantResolved $event): void
    {
        Log::debug('Tenant resolved', ['tenant_id' => $event->tenantId]);
    }
}
