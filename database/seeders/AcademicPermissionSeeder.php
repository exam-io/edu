<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AcademicPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'department.*',
        'department.view',
        'department.create',
        'department.update',
        'department.delete',
        'program.*',
        'program.view',
        'program.create',
        'program.update',
        'program.delete',
        'class.*',
        'class.view',
        'class.create',
        'class.update',
        'class.delete',
        'section.*',
        'section.view',
        'section.create',
        'section.update',
        'section.delete',
        'batch.*',
        'batch.view',
        'batch.create',
        'batch.update',
        'batch.delete',
        'subject.*',
        'subject.view',
        'subject.create',
        'subject.update',
        'subject.delete',
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
