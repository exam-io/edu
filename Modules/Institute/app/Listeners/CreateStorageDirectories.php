<?php

namespace Modules\Institute\Listeners;

use Illuminate\Support\Facades\Storage;
use Modules\Institute\Domain\Events\InstituteRegistered;
use Modules\Institute\Domain\Models\Institute;

class CreateStorageDirectories
{
    public function handle(InstituteRegistered $event): void
    {
        $institute = Institute::query()->find($event->instituteId);

        if ($institute === null) {
            return;
        }

        $basePath = "tenants/{$institute->tenant_id}/institutes/{$institute->id}";

        foreach (['documents', 'uploads', 'exports', 'branding'] as $dir) {
            Storage::disk('local')->makeDirectory("{$basePath}/{$dir}");
        }
    }
}
