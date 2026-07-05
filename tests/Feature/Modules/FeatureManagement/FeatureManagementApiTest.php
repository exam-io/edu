<?php

namespace Tests\Feature\Modules\FeatureManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class FeatureManagementApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_flags_upsert_and_evaluate_work(): void
    {
        $tenant = $this->createTenant('fm-a');
        $user = $this->createUser($tenant, 'fm-a@test.local');

        Permission::findOrCreate('feature-management.view', 'web');
        Permission::findOrCreate('feature-management.manage', 'web');
        $user->givePermissionTo(['feature-management.view', 'feature-management.manage']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->putJson('/api/v1/feature-management/flags', [
                'flags' => [
                    ['feature_key' => 'custom.domain', 'enabled' => true],
                ],
            ])
            ->assertOk();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/feature-management/evaluate?feature_key=custom.domain')
            ->assertOk()
            ->assertJsonPath('data.enabled', true);
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
            'name' => 'Feature User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
