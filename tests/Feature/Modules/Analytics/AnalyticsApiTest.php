<?php

namespace Tests\Feature\Modules\Analytics;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AnalyticsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_track_and_list_endpoints_work_with_permissions(): void
    {
        $tenant = $this->createTenant('analytics-a');
        $user = $this->createUser($tenant, 'analytics-user@test.local');

        Permission::findOrCreate('analytics.view', 'web');
        Permission::findOrCreate('analytics.track', 'web');
        $user->givePermissionTo(['analytics.view', 'analytics.track']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/analytics/events', [
                'event_name' => 'manual.test.event',
                'source_module' => 'Analytics',
                'payload' => ['sample' => true],
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.event_name', 'manual.test.event');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/analytics/events')
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
            'name' => 'Analytics API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
