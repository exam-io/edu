<?php

namespace Tests\Feature\Modules\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Parent\Domain\Models\ParentProfile;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_crud_is_tenant_scoped_and_supports_search_and_pagination(): void
    {
        $tenantA = $this->createTenant('student-a');
        $tenantB = $this->createTenant('student-b');
        $user = $this->createUserWithPermissions($tenantA, 'student-admin@a.test', [
            'student.view',
            'student.create',
            'student.update',
            'student.delete',
            'parent.create',
        ]);

        $parentA = ParentProfile::query()->create([
            'tenant_id' => $tenantA->id,
            'first_name' => 'Parent',
            'last_name' => 'One',
            'relationship' => 'father',
            'phone' => '1111111111',
            'status' => 'active',
        ]);

        $otherTenantParent = ParentProfile::query()->create([
            'tenant_id' => $tenantB->id,
            'first_name' => 'Parent',
            'last_name' => 'Two',
            'relationship' => 'mother',
            'phone' => '2222222222',
            'status' => 'active',
        ]);

        Student::query()->create([
            'tenant_id' => $tenantA->id,
            'admission_no' => 'A-0001',
            'first_name' => 'Visible',
            'last_name' => 'Student',
            'gender' => 'other',
            'date_of_birth' => '2010-01-01',
            'admission_date' => '2026-06-01',
            'status' => 'active',
        ]);

        Student::query()->create([
            'tenant_id' => $tenantB->id,
            'admission_no' => 'B-0001',
            'first_name' => 'Hidden',
            'last_name' => 'Student',
            'gender' => 'other',
            'date_of_birth' => '2010-01-01',
            'admission_date' => '2026-06-01',
            'status' => 'active',
        ]);

        Sanctum::actingAs($user);

        $listResponse = $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->getJson('/api/students?search=Visible&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Visible');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->postJson('/api/students', [
                'admission_no' => 'A-0002',
                'first_name' => 'New',
                'last_name' => 'Learner',
                'gender' => 'female',
                'date_of_birth' => '2012-04-01',
                'admission_date' => '2026-07-01',
                'status' => 'active',
                'parent_ids' => [(int) $otherTenantParent->id],
                'primary_parent_id' => (int) $otherTenantParent->id,
            ])
            ->assertStatus(422);

        $createResponse = $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->postJson('/api/students', [
                'admission_no' => 'A-0002',
                'first_name' => 'New',
                'last_name' => 'Learner',
                'gender' => 'female',
                'date_of_birth' => '2012-04-01',
                'admission_date' => '2026-07-01',
                'status' => 'active',
                'parent_ids' => [(int) $parentA->id],
                'primary_parent_id' => (int) $parentA->id,
            ]);

        $studentId = (int) $createResponse->json('data.id');

        $createResponse
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.admission_no', 'A-0002');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->putJson('/api/students/'.$studentId, [
                'first_name' => 'Updated',
                'status' => 'inactive',
            ])
            ->assertOk()
            ->assertJsonPath('data.first_name', 'Updated')
            ->assertJsonPath('data.status', 'inactive');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->deleteJson('/api/students/'.$studentId)
            ->assertOk();

        $this->assertSoftDeleted('students', ['id' => $studentId]);
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
            'name' => 'Student API User',
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
