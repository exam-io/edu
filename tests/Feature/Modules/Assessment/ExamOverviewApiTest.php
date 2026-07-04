<?php

namespace Tests\Feature\Modules\Assessment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ExamOverviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_overview_is_tenant_scoped(): void
    {
        $tenantA = $this->createTenant('exam-a');
        $tenantB = $this->createTenant('exam-b');

        $userA = $this->createUser($tenantA, 'exam-a@test.local');
        Permission::findOrCreate('assessment_result.view', 'web');
        $userA->givePermissionTo(['assessment_result.view']);

        Assessment::query()->create([
            'tenant_id' => $tenantA->id,
            'title' => 'Tenant A Quiz',
            'type' => 'Quiz',
            'total_marks' => 100,
            'passing_marks' => 40,
            'status' => 'published',
            'created_by' => $userA->id,
        ]);

        Assessment::query()->create([
            'tenant_id' => $tenantA->id,
            'title' => 'Tenant A Assignment',
            'type' => 'Assignment',
            'total_marks' => 100,
            'passing_marks' => 40,
            'status' => 'draft',
            'created_by' => $userA->id,
        ]);

        $userB = $this->createUser($tenantB, 'exam-b@test.local');
        Assessment::query()->create([
            'tenant_id' => $tenantB->id,
            'title' => 'Tenant B Quiz',
            'type' => 'Quiz',
            'total_marks' => 100,
            'passing_marks' => 40,
            'status' => 'published',
            'created_by' => $userB->id,
        ]);

        Sanctum::actingAs($userA);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->getJson('/api/exams/overview')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_assessments', 2)
            ->assertJsonPath('data.published_assessments', 1);
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
            'name' => 'Exam API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
