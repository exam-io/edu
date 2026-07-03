<?php

namespace Tests\Feature\Modules\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Teacher\Domain\Models\Teacher;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class TeacherApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_crud_is_tenant_scoped_with_search_and_pagination(): void
    {
        $tenantA = $this->createTenant('teacher-a');
        $tenantB = $this->createTenant('teacher-b');

        $user = $this->createUserWithPermissions($tenantA, 'teacher-admin@a.test', [
            'teacher.view',
            'teacher.create',
            'teacher.update',
            'teacher.delete',
        ]);

        Teacher::query()->create([
            'tenant_id' => $tenantA->id,
            'employee_no' => 'EMP-A-001',
            'first_name' => 'Visible',
            'last_name' => 'Teacher',
            'gender' => 'male',
            'joining_date' => '2024-01-01',
            'status' => 'active',
        ]);

        Teacher::query()->create([
            'tenant_id' => $tenantB->id,
            'employee_no' => 'EMP-B-001',
            'first_name' => 'Hidden',
            'last_name' => 'Teacher',
            'gender' => 'female',
            'joining_date' => '2024-01-01',
            'status' => 'active',
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->getJson('/api/teachers?search=Visible&per_page=1')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Visible');

        $createResponse = $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->postJson('/api/teachers', [
                'employee_no' => 'EMP-A-002',
                'first_name' => 'New',
                'last_name' => 'Teacher',
                'gender' => 'female',
                'joining_date' => '2025-05-01',
                'status' => 'active',
            ]);

        $teacherId = (int) $createResponse->json('data.id');

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.employee_no', 'EMP-A-002');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->putJson('/api/teachers/'.$teacherId, [
                'status' => 'inactive',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'inactive');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->deleteJson('/api/teachers/'.$teacherId)
            ->assertOk();

        $this->assertSoftDeleted('teachers', ['id' => $teacherId]);
    }

    private function createTenant(string $slug): Tenant
    {
        return Tenant::query()->create([
            'name' => ucfirst($slug).' Institute',
            'slug' => $slug,
            'domain' => $slug.'.localhost',
            'status' => 'active',
        ]);
    }

    private function createUserWithPermissions(Tenant $tenant, string $email, array $permissions): User
    {
        $user = User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Teacher API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo($permissions);

        return $user;
    }
}
