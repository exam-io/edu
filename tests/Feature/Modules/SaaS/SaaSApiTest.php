<?php

namespace Tests\Feature\Modules\SaaS;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SaaSApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_saas_usage_tracking_dashboard_and_snapshot_work(): void
    {
        $tenant = $this->createTenant('saas-a');
        $user = $this->createUser($tenant, 'saas-a@test.local');

        foreach (['saas.dashboard.view', 'saas.dashboard.manage', 'saas.usage.view', 'saas.usage.manage'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo(['saas.dashboard.view', 'saas.dashboard.manage', 'saas.usage.view', 'saas.usage.manage']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/saas/usage/track', [
                'metric_key' => 'api_calls',
                'increment_by' => 10,
                'period_key' => now()->format('Y-m'),
            ])
            ->assertOk()
            ->assertJsonPath('data.metric_key', 'api_calls');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/saas/usage')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/saas/snapshots', [
                'snapshot_date' => now()->toDateString(),
            ])
            ->assertStatus(202)
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/saas/dashboard')
            ->assertOk()
            ->assertJsonPath('success', true);
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
            'name' => 'SaaS User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
