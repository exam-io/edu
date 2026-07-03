<?php

namespace Modules\Tenant\Listeners;

use Illuminate\Support\Facades\Log;

class LogTenantStatusChange
{
    public function handle(object $event): void
    {
        $tenantId = property_exists($event, 'tenantId') ? $event->tenantId : null;

        Log::info('Tenant status event dispatched.', [
            'event' => $event::class,
            'tenant_id' => $tenantId,
        ]);
    }
}
