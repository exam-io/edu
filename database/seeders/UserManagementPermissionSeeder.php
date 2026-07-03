<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findByName('Super Admin', 'web');
        $instituteAdmin = Role::findByName('Institute Admin', 'web');

        $superAdmin->givePermissionTo(self::PERMISSIONS);
        $instituteAdmin->givePermissionTo(array_filter(self::PERMISSIONS, static fn (string $permission) => !str_ends_with($permission, '.*')));
    }
}
