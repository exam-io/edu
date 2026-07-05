<?php

namespace Tests\Feature\Modules\Step16;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ProductionHardeningApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_step16_endpoints_work_with_permissions(): void
    {
        $tenant = $this->createTenant('step16-a');
        $user = $this->createUser($tenant, 'step16-admin@test.local');

        $permissions = [
            'system.security.view',
            'system.security.manage',
            'system.health.view',
            'monitoring.view',
            'monitoring.manage',
            'monitoring.alert.manage',
            'audit.view',
            'audit.create',
            'operations.view',
            'operations.queue.manage',
            'operations.backup.view',
            'operations.backup.run',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo($permissions);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/system/security-policy')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/monitoring/metrics/aggregate', [
                'metric_key' => 'queue_backlog',
                'value' => 20,
            ])
            ->assertStatus(202)
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/monitoring/alert-rules', [
                'metric_key' => 'queue_backlog',
                'operator' => '>=',
                'threshold' => 10,
                'severity' => 'warning',
                'is_active' => true,
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/audit/logs', [
                'action' => 'system.policy.updated',
                'resource_type' => 'system_policy',
                'after_state' => ['token' => 'abc', 'setting' => 'enabled'],
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/audit/logs')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/operations/backups/latest')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/operations/queue/logs', [
                'operation' => 'queue.retry.failed_jobs',
                'meta' => ['source' => 'test'],
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/operations/backups/trigger', [])
            ->assertStatus(202)
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
            'name' => 'Step16 User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
