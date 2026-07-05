<?php

namespace Tests\Feature\Modules\MobileProvisioning;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MobileProvisioningApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_config_show_and_publish_work(): void
    {
        $tenant = $this->createTenant('mp-a');
        $user = $this->createUser($tenant, 'mp-a@test.local');

        Permission::findOrCreate('mobile-provisioning.view', 'web');
        Permission::findOrCreate('mobile-provisioning.manage', 'web');
        Permission::findOrCreate('branding.manage', 'web');
        Permission::findOrCreate('feature-management.manage', 'web');
        Permission::findOrCreate('white-label.navigation.manage', 'web');
        Permission::findOrCreate('white-label.navigation.view', 'web');

        $user->givePermissionTo(['mobile-provisioning.view', 'mobile-provisioning.manage']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/mobile-provisioning/config')
            ->assertOk();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/mobile-provisioning/publish')
            ->assertOk();
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
            'name' => 'Mobile User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
