<?php

namespace Tests\Feature\Modules\Admissions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdmissionsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_store_and_status_update_flow(): void
    {
        $tenant = $this->createTenant('adm-a');
        $user = $this->createUser($tenant, 'adm-a@test.local');

        Permission::findOrCreate('admissions.application.create', 'web');
        Permission::findOrCreate('admissions.application.view', 'web');
        Permission::findOrCreate('admissions.application.update', 'web');
        $user->givePermissionTo([
            'admissions.application.create',
            'admissions.application.view',
            'admissions.application.update',
        ]);

        Sanctum::actingAs($user);

        $store = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/admissions/applications', [
                'first_name' => 'Alice',
                'email' => 'alice-adm@test.local',
            ])
            ->assertCreated();

        $id = (int) $store->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/admissions/applications/' . $id . '/status', [
                'status' => 'under_review',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'under_review');
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
            'name' => 'Admissions User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
