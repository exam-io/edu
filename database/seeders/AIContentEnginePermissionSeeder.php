<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AIContentEnginePermissionSeeder extends Seeder
{
    /** @var list<string> */
    private const PERMISSIONS = [
        'ai.request.*',
        'ai.request.view',
        'ai.request.create',
        'ai.request.delete',
        'content.processing.*',
        'content.processing.view',
        'content.processing.create',
        'content.processing.update',
        'content.processing.delete',
        'question.bank.*',
        'question.bank.view',
        'question.bank.create',
        'question.bank.update',
        'question.bank.delete',
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
