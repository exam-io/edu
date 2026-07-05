<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AnalyticsReportingBiPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'analytics.*',
        'analytics.view',
        'analytics.track',
        'reporting.*',
        'reporting.view',
        'reporting.create',
        'reporting.run',
        'reporting.schedule',
        'dashboard.*',
        'dashboard.view',
        'dashboard.configure',
        'insights.*',
        'insights.view',
        'insights.generate',
    ];

    public function run(): void
    {
        $permissions = collect(self::PERMISSIONS)
            ->map(static fn (string $permission): Permission => Permission::findOrCreate($permission, 'web'));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdmin = Role::findByName('Super Admin', 'web');
        $instituteAdmin = Role::findByName('Institute Admin', 'web');
        $teacher = Role::findByName('Teacher', 'web');
        $student = Role::findByName('Student', 'web');
        $parent = Role::findByName('Parent', 'web');

        $superAdmin->syncPermissions($permissions);

        $instituteAdmin->givePermissionTo([
            'analytics.view',
            'analytics.track',
            'reporting.view',
            'reporting.create',
            'reporting.run',
            'reporting.schedule',
            'dashboard.view',
            'dashboard.configure',
            'insights.view',
            'insights.generate',
        ]);

        $teacher->givePermissionTo([
            'analytics.view',
            'reporting.view',
            'reporting.run',
            'dashboard.view',
            'dashboard.configure',
            'insights.view',
        ]);

        $student->givePermissionTo([
            'dashboard.view',
            'dashboard.configure',
            'insights.view',
        ]);

        $parent->givePermissionTo([
            'dashboard.view',
            'insights.view',
        ]);
    }
}
