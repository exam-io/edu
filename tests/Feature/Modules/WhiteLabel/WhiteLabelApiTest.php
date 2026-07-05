<?php

namespace Tests\Feature\Modules\WhiteLabel;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class WhiteLabelApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_create_and_navigation_show_work(): void
    {
        $tenant = $this->createTenant('wl-a');
        $user = $this->createUser($tenant, 'wl-a@test.local');

        Permission::findOrCreate('white-label.domain.view', 'web');
        Permission::findOrCreate('white-label.domain.manage', 'web');
        Permission::findOrCreate('white-label.navigation.view', 'web');
        Permission::findOrCreate('white-label.navigation.manage', 'web');
        $user->givePermissionTo([
            'white-label.domain.view',
            'white-label.domain.manage',
            'white-label.navigation.view',
            'white-label.navigation.manage',
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/white-label/domains', [
                'host' => 'school.example.test',
                'is_primary' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.host', 'school.example.test');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/white-label/navigation')
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
            'name' => 'WhiteLabel User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
