<?php

namespace Modules\Tenant\Listeners;

use Modules\Tenant\Domain\Events\TenantCreated;
use Modules\Tenant\Domain\Models\TenantSetting;

class InitializeTenantSettings
{
    public function handle(TenantCreated $event): void
    {
        TenantSetting::query()->updateOrCreate(
            ['tenant_id' => $event->tenantId],
            [
                'theme' => 'light',
                'language' => config('app.locale'),
                'timezone' => config('app.timezone'),
                'primary_color' => '#0b6eff',
                'secondary_color' => '#00a889',
            ]
        );
    }
}
