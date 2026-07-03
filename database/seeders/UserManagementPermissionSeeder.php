<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserManagementPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'student.*',
        'student.view',
        'student.create',
        'student.update',
        'student.delete',
        'teacher.*',
        'teacher.view',
        'teacher.create',
        'teacher.update',
        'teacher.delete',
        'parent.*',
        'parent.view',
        'parent.create',
        'parent.update',
        'parent.delete',
        'enrollment.*',
        'enrollment.view',
        'enrollment.create',
        'enrollment.update',
        'enrollment.delete',
        'teacher_assignment.*',
        'teacher_assignment.view',
        'teacher_assignment.create',
        'teacher_assignment.update',
        'teacher_assignment.delete',
    ];

    public function run(): void
    {
        $permissions = collect(self::PERMISSIONS)
            ->map(static fn (string $permission): Permission => Permission::findOrCreate($permission, 'web'));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdmin = Role::findByName('Super Admin', 'web');
        $instituteAdmin = Role::findByName('Institute Admin', 'web');

        $superAdmin->syncPermissions($permissions);
        $instituteAdmin->syncPermissions(
            $permissions->reject(static fn (Permission $permission): bool => str_ends_with($permission->name, '.*'))->values()
        );
    }
}
