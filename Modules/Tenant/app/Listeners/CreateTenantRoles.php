<?php

namespace Modules\Tenant\Listeners;

use Illuminate\Support\Facades\Schema;
use Modules\Tenant\Domain\Events\TenantCreated;
use Spatie\Permission\Models\Role;

class CreateTenantRoles
{
    public function handle(TenantCreated $event): void
    {
        $teamColumn = config('permission.column_names.team_foreign_key', 'tenant_id');
        $guard = config('auth.defaults.guard', 'web');
        $roles = ['tenant-admin', 'tenant-manager', 'tenant-staff'];
        $usesTeams = (bool) config('permission.teams', false) && Schema::hasColumn('roles', $teamColumn);

        if ($usesTeams && function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($event->tenantId);
        }

        foreach ($roles as $name) {
            $attributes = [
                'name' => $name,
                'guard_name' => $guard,
            ];

            if ($usesTeams) {
                $attributes[$teamColumn] = $event->tenantId;
            }

            Role::query()->firstOrCreate($attributes);
        }
    }
}
