<?php

namespace Tests\Feature\Modules\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_me_and_preference_update_endpoints(): void
    {
        $tenant = $this->createTenant('dashboard-a');
        $user = $this->createUser($tenant, 'dashboard-user@test.local');

        Permission::findOrCreate('dashboard.view', 'web');
        Permission::findOrCreate('dashboard.configure', 'web');
        $user->givePermissionTo(['dashboard.view', 'dashboard.configure']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/dashboards/me')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->putJson('/api/dashboards/preferences', [
                'preferences' => [
                    'layout' => 'compact',
                    'widgets' => ['events', 'insights'],
                ],
            ])
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
            'name' => 'Dashboard API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
