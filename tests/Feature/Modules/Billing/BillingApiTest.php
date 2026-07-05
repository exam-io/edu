<?php

namespace Tests\Feature\Modules\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class BillingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_billing_center_invoice_generation_and_profile_update_work(): void
    {
        $tenant = $this->createTenant('billing-a');
        $user = $this->createUser($tenant, 'billing-a@test.local');

        foreach (['billing.view', 'billing.invoice.view', 'billing.invoice.manage', 'billing.manage'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo(['billing.view', 'billing.invoice.view', 'billing.invoice.manage', 'billing.manage']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/billing/invoices/generate', [
                'currency' => 'USD',
                'period_start' => now()->subMonth()->startOfMonth()->toDateString(),
                'period_end' => now()->subMonth()->endOfMonth()->toDateString(),
                'line_items' => [
                    [
                        'description' => 'Plan fee',
                        'quantity' => 1,
                        'unit_amount' => 120,
                        'tax_amount' => 12,
                    ],
                ],
            ])
            ->assertStatus(202)
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/billing/center')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->putJson('/api/v1/billing/profile', [
                'legal_name' => 'Billing Institute',
                'email' => 'accounts@billing.test',
                'currency' => 'USD',
            ])
            ->assertOk()
            ->assertJsonPath('data.legal_name', 'Billing Institute');
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
            'name' => 'Billing User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
