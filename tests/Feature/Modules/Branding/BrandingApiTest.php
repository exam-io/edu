<?php

namespace Tests\Feature\Modules\Branding;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class BrandingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_branding_current_and_update_work_with_permissions(): void
    {
        $tenant = $this->createTenant('branding-a');
        $user = $this->createUser($tenant, 'branding-a@test.local');

        Permission::findOrCreate('branding.view', 'web');
        Permission::findOrCreate('branding.manage', 'web');
        $user->givePermissionTo(['branding.view', 'branding.manage']);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/branding/current')
            ->assertOk();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->putJson('/api/v1/branding/current', [
                'name' => 'Branding Test',
                'primary_color' => '#112233',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Branding Test');
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
            'name' => 'Branding User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
