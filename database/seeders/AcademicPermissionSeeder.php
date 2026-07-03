<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findByName('Super Admin', 'web');
        $instituteAdmin = Role::findByName('Institute Admin', 'web');

        $superAdmin->givePermissionTo(self::PERMISSIONS);
        $instituteAdmin->givePermissionTo(array_filter(self::PERMISSIONS, static fn (string $permission) => !str_ends_with($permission, '.*')));
    }
}
