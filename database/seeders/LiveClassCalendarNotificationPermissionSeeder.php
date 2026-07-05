<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class LiveClassCalendarNotificationPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'live_class.*',
        'live_class.view',
        'live_class.create',
        'live_class.update',
        'live_class.delete',
        'live_class.start',
        'live_class.join',
        'live_class.attendance.view',
        'calendar.*',
        'calendar.view',
        'calendar.create',
        'calendar.update',
        'calendar.delete',
        'notification.*',
        'notification.view',
        'notification.read',
        'notification.device_token.manage',
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

        $superAdmin->syncPermissions($permissions);

        $instituteAdmin->syncPermissions(
            $permissions->reject(static fn (Permission $permission): bool => str_ends_with($permission->name, '.*'))->values()
        );

        $teacher->givePermissionTo([
            'live_class.view',
            'live_class.create',
            'live_class.update',
            'live_class.start',
            'live_class.attendance.view',
            'calendar.view',
            'calendar.create',
            'calendar.update',
            'notification.view',
            'notification.read',
            'notification.device_token.manage',
        ]);

        $student->givePermissionTo([
            'live_class.view',
            'live_class.join',
            'calendar.view',
            'notification.view',
            'notification.read',
            'notification.device_token.manage',
        ]);
    }
}
