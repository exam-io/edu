<?php

namespace Tests\Feature\Modules\CRM;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CRMLeadApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_index_and_store_require_permissions(): void
    {
        $tenant = $this->createTenant('crm-a');
        $user = $this->createUser($tenant, 'crm-a@test.local');

        Permission::findOrCreate('crm.lead.view', 'web');
        Permission::findOrCreate('crm.lead.create', 'web');
        $user->givePermissionTo(['crm.lead.view', 'crm.lead.create']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/crm/leads', [
                'first_name' => 'Lead',
                'email' => 'lead-a@test.local',
            ])
            ->assertCreated()
            ->assertJsonPath('data.email', 'lead-a@test.local');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/crm/leads')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
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
            'name' => 'CRM User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
