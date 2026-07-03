<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DemoUsersSeeder extends Seeder
{
    /**
     * Seed stable demo users with shared credentials for quick testing.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $password = Hash::make('password123');

        $tenants = [
            'north-campus' => Tenant::query()->updateOrCreate(
                ['slug' => 'north-campus-demo'],
                [
                    'name' => 'North Campus Demo',
                    'domain' => 'north-campus-demo.edus.test',
                    'status' => 'active',
                ],
            ),
            'south-campus' => Tenant::query()->updateOrCreate(
                ['slug' => 'south-campus-demo'],
                [
                    'name' => 'South Campus Demo',
                    'domain' => 'south-campus-demo.edus.test',
                    'status' => 'active',
                ],
            ),
        ];

        $users = [
            ['name' => 'Super Admin', 'email' => 'admin.demo@edus.test', 'status' => 'active', 'role' => 'Super Admin', 'tenant' => 'north-campus'],
            ['name' => 'Institute Manager', 'email' => 'manager.demo@edus.test', 'status' => 'active', 'role' => 'Institute Admin', 'tenant' => 'north-campus'],
            ['name' => 'Academic Coordinator', 'email' => 'coordinator.demo@edus.test', 'status' => 'active', 'role' => 'Institute Admin', 'tenant' => 'north-campus'],
            ['name' => 'Admissions Officer', 'email' => 'admissions.demo@edus.test', 'status' => 'active', 'role' => 'Institute Admin', 'tenant' => 'north-campus'],
            ['name' => 'Faculty Lead', 'email' => 'faculty.demo@edus.test', 'status' => 'active', 'role' => 'Teacher', 'tenant' => 'north-campus'],
            ['name' => 'Registrar User', 'email' => 'registrar.demo@edus.test', 'status' => 'active', 'role' => 'Institute Admin', 'tenant' => 'south-campus'],
            ['name' => 'Student Demo', 'email' => 'student.demo@edus.test', 'status' => 'active', 'role' => 'Student', 'tenant' => 'south-campus'],
            ['name' => 'Parent Demo', 'email' => 'parent.demo@edus.test', 'status' => 'active', 'role' => 'Parent', 'tenant' => 'south-campus'],
            ['name' => 'Demo Inactive', 'email' => 'inactive.demo@edus.test', 'status' => 'inactive', 'role' => 'Student', 'tenant' => 'south-campus'],
            ['name' => 'Demo Suspended', 'email' => 'suspended.demo@edus.test', 'status' => 'suspended', 'role' => 'Parent', 'tenant' => 'south-campus'],
        ];

        foreach ($users as $user) {
            $roleName = $user['role'];
            Role::findOrCreate($roleName, 'web');
            $tenantId = $tenants[$user['tenant']]->id;

            $account = User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'tenant_id' => $tenantId,
                    'name' => $user['name'],
                    'password' => $password,
                    'status' => $user['status'],
                    'email_verified_at' => now(),
                ],
            );

            $account->syncRoles([$roleName]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
