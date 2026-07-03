<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CourseLmsPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'course.*',
        'course.view',
        'course.create',
        'course.update',
        'course.delete',
        'content.section.*',
        'content.section.view',
        'content.section.create',
        'content.section.update',
        'content.section.delete',
        'content.item.*',
        'content.item.view',
        'content.item.create',
        'content.item.update',
        'content.item.delete',
        'media.*',
        'media.view',
        'media.create',
        'media.update',
        'media.delete',
        'lms.enrollment.*',
        'lms.enrollment.view',
        'lms.enrollment.create',
        'lms.enrollment.update',
        'lms.enrollment.delete',
        'lms.progress.*',
        'lms.progress.view',
        'lms.progress.create',
        'lms.progress.update',
        'lms.progress.delete',
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
