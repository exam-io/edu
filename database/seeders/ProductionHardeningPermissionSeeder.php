<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ProductionHardeningPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'system.*',
        'system.security.view',
        'system.security.manage',
        'system.health.view',
        'monitoring.*',
        'monitoring.view',
        'monitoring.manage',
        'monitoring.alert.manage',
        'audit.*',
        'audit.view',
        'audit.create',
        'operations.*',
        'operations.view',
        'operations.queue.manage',
        'operations.backup.view',
        'operations.backup.run',
    ];

    public function run(): void
    {
        $permissions = collect(self::PERMISSIONS)
            ->map(static fn (string $permission): Permission => Permission::findOrCreate($permission, 'web'));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdmin = Role::findByName('Super Admin', 'web');
        $instituteAdmin = Role::findByName('Institute Admin', 'web');
        $teacher = Role::findByName('Teacher', 'web');

        $superAdmin->syncPermissions($permissions);

        $instituteAdmin->givePermissionTo([
            'system.security.view',
            'system.security.manage',
            'system.health.view',
            'monitoring.view',
            'monitoring.manage',
            'monitoring.alert.manage',
            'audit.view',
            'audit.create',
            'operations.view',
            'operations.queue.manage',
            'operations.backup.view',
            'operations.backup.run',
        ]);

        $teacher->givePermissionTo([
            'system.health.view',
            'monitoring.view',
            'audit.view',
        ]);
    }
}
