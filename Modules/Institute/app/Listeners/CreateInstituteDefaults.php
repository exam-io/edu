<?php

namespace Modules\Institute\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\Institute\Domain\Events\InstituteRegistered;
use Modules\Institute\Domain\Events\InstituteUpdated;
use Modules\Institute\Domain\Models\Institute;
use Spatie\Permission\Models\Role;

class CreateInstituteDefaults
{
    public function handle(InstituteRegistered $event): void
    {
        $institute = Institute::query()->find($event->instituteId);

        if ($institute === null) {
            return;
        }

        $institute->update([
            'branding' => array_merge([
                'logo_url' => null,
                'primary_color' => '#0b6eff',
                'secondary_color' => '#00a889',
                'tagline' => null,
            ], is_array($institute->branding) ? $institute->branding : []),
            'configuration' => array_merge([
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'features' => ['onboarding', 'academic-session-management'],
            ], is_array($institute->configuration) ? $institute->configuration : []),
            'onboarding_step' => 'defaults_initialized',
        ]);

        $this->assignInstituteAdminRole($institute, $event->actorUserId);

        event(new InstituteUpdated($institute->id, $event->actorUserId));
    }

    private function assignInstituteAdminRole(Institute $institute, ?int $actorUserId): void
    {
        if ($actorUserId === null) {
            return;
        }

        $user = User::query()
            ->whereKey($actorUserId)
            ->where('tenant_id', $institute->tenant_id)
            ->first();

        if ($user === null) {
            return;
        }

        $teamColumn = config('permission.column_names.team_foreign_key', 'tenant_id');
        $guard = config('auth.defaults.guard', 'web');
        $usesTeams = (bool) config('permission.teams', false) && Schema::hasColumn('roles', $teamColumn);

        if ($usesTeams && function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($institute->tenant_id);
        }

        $attributes = [
            'name' => 'institute-admin',
            'guard_name' => $guard,
        ];

        if ($usesTeams) {
            $attributes[$teamColumn] = $institute->tenant_id;
        }

        $role = Role::query()->firstOrCreate($attributes);
        $user->assignRole($role);
    }
}
