<?php

namespace Tests\Feature\Modules\Shared;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Domain\Models\Tenant;
use Tests\TestCase;

class SharedLookupApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_lookup_is_tenant_scoped(): void
    {
        $tenantA = $this->createTenant('shared-a');
        $tenantB = $this->createTenant('shared-b');

        $user = $this->createUser($tenantA, 'shared-a@edus.test');

        Student::query()->create([
            'tenant_id' => $tenantA->id,
            'admission_no' => 'A-001',
            'first_name' => 'Visible',
            'last_name' => 'Student',
            'gender' => 'other',
            'date_of_birth' => '2010-01-01',
            'admission_date' => '2026-06-01',
            'status' => 'active',
        ]);

        Student::query()->create([
            'tenant_id' => $tenantB->id,
            'admission_no' => 'B-001',
            'first_name' => 'Hidden',
            'last_name' => 'Student',
            'gender' => 'other',
            'date_of_birth' => '2010-01-01',
            'admission_date' => '2026-06-01',
            'status' => 'active',
        ]);

        Sanctum::actingAs($user);

        $response = $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->getJson('/api/v1/shareds?type=students');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', 'Visible Student');
    }

    public function test_shared_lookup_create_is_method_not_allowed(): void
    {
        $tenant = $this->createTenant('shared-c');
        $user = $this->createUser($tenant, 'shared-c@edus.test');

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/shareds', [
                'type' => 'students',
            ])
            ->assertStatus(405)
            ->assertJsonPath('success', false);
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
            'name' => 'Shared User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
