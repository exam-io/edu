<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AssessmentExamPermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'assessment.*',
        'assessment.view',
        'assessment.create',
        'assessment.update',
        'assessment.delete',
        'assessment.publish',
        'assessment.publish.override',
        'assessment_attempt.*',
        'assessment_attempt.create',
        'assessment_attempt.update',
        'assessment_attempt.submit',
        'assessment_result.*',
        'assessment_result.view',
        'assignment.*',
        'assignment.submit',
        'submission.*',
        'submission.view',
        'submission.evaluate',
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
            'assessment.view',
            'assessment.create',
            'assessment.update',
            'assessment.publish',
            'assessment_result.view',
            'submission.view',
            'submission.evaluate',
        ]);

        $student->givePermissionTo([
            'assessment.view',
            'assessment_attempt.create',
            'assessment_attempt.update',
            'assessment_attempt.submit',
            'assessment_result.view',
            'assignment.submit',
        ]);
    }
}
