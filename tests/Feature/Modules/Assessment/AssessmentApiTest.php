<?php

namespace Tests\Feature\Modules\Assessment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AssessmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_assessment_endpoints_require_permissions_and_enforce_tenant_scope(): void
    {
        $tenantA = $this->createTenant('assess-a');
        $tenantB = $this->createTenant('assess-b');

        $userA = $this->createUser($tenantA, 'assessment-a@test.local');
        Permission::findOrCreate('assessment.view', 'web');
        Permission::findOrCreate('assessment.create', 'web');
        $userA->givePermissionTo(['assessment.view', 'assessment.create']);

        Sanctum::actingAs($userA);

        $create = $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->postJson('/api/assessments', [
                'title' => 'Tenant A Quiz',
                'type' => 'Quiz',
                'total_marks' => 100,
                'passing_marks' => 40,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Tenant A Quiz');

        $assessmentId = (int) $create->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantB->id)
            ->getJson('/api/assessments/' . $assessmentId)
            ->assertNotFound();
    }

    private function createTenant(string $slug): Tenant
    {
        return Tenant::query()->create([
            'name' => ucfirst($slug) . ' Institute',
            'slug' => $slug,
            'domain' => $slug . '.localhost',
            'status' => 'active',
        ]);
    }

    private function createUser(Tenant $tenant, string $email): User
    {
        return User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Assessment API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
