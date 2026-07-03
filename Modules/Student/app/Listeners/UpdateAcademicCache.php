<?php

namespace Modules\Student\Listeners;

use Illuminate\Support\Facades\Cache;

class UpdateAcademicCache
{
    public function handle(object $event): void
    {
        $tenantId = property_exists($event, 'tenantId') ? (int) $event->tenantId : null;
        if ($tenantId === null) {
            return;
        }

        Cache::forget("academic:tenant:{$tenantId}:structure");
    }
}
