<?php

namespace Tests\Feature\Modules\Subscription;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SubscriptionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_plan_and_tenant_subscription_endpoints_work(): void
    {
        $tenant = $this->createTenant('sub-a');
        $user = $this->createUser($tenant, 'sub-a@test.local');

        foreach (['subscription.plan.view', 'subscription.plan.manage', 'subscription.view', 'subscription.manage'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo(['subscription.plan.view', 'subscription.plan.manage', 'subscription.view', 'subscription.manage']);

        Sanctum::actingAs($user);

        $planResponse = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/subscription/plans', [
                'code' => 'pro-monthly',
                'name' => 'Pro Monthly',
                'billing_interval' => 'monthly',
                'price_amount' => 99.00,
                'currency' => 'USD',
                'quota' => ['api_calls' => 20000],
            ])
            ->assertOk();

        $planId = (int) $planResponse->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->putJson('/api/v1/subscription/tenant', [
                'plan_id' => $planId,
                'status' => 'active',
                'starts_at' => now()->toDateString(),
                'renews_at' => now()->addMonth()->toDateString(),
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'active');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/subscription/current')
            ->assertOk()
            ->assertJsonPath('data.status', 'active');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/subscription/renew')
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
            'name' => 'Subscription User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
