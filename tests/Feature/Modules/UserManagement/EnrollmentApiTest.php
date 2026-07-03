<?php

namespace Tests\Feature\Modules\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class EnrollmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_and_teacher_assignment_index_require_correct_permissions(): void
    {
        $tenant = $this->createTenant('enroll-a');
        $userWithoutPermissions = $this->createUser($tenant, 'enroll-user-no-perm@a.test');

        Sanctum::actingAs($userWithoutPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/enrollments')
            ->assertForbidden();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/teacher-assignments')
            ->assertForbidden();

        $userWithPermissions = $this->createUser($tenant, 'enroll-user-with-perm@a.test');
        Permission::findOrCreate('enrollment.view', 'web');
        Permission::findOrCreate('teacher_assignment.view', 'web');
        $userWithPermissions->givePermissionTo(['enrollment.view', 'teacher_assignment.view']);

        Sanctum::actingAs($userWithPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/enrollments?per_page=5')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 5);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/teacher-assignments?per_page=5')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 5);
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

    private function createUser(Tenant $tenant, string $email): User
    {
        return User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Enrollment API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
