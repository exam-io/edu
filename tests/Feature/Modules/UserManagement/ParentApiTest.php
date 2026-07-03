<?php

namespace Tests\Feature\Modules\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Parent\Domain\Models\ParentProfile;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ParentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_crud_is_tenant_scoped_with_search_and_pagination(): void
    {
        $tenantA = $this->createTenant('parent-a');
        $tenantB = $this->createTenant('parent-b');

        $user = $this->createUserWithPermissions($tenantA, 'parent-admin@a.test', [
            'parent.view',
            'parent.create',
            'parent.update',
            'parent.delete',
        ]);

        ParentProfile::query()->create([
            'tenant_id' => $tenantA->id,
            'first_name' => 'Visible',
            'last_name' => 'Parent',
            'relationship' => 'mother',
            'phone' => '1234567890',
            'status' => 'active',
        ]);

        ParentProfile::query()->create([
            'tenant_id' => $tenantB->id,
            'first_name' => 'Hidden',
            'last_name' => 'Parent',
            'relationship' => 'father',
            'phone' => '0987654321',
            'status' => 'active',
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->getJson('/api/parents?search=Visible&per_page=1')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Visible');

        $createResponse = $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->postJson('/api/parents', [
                'first_name' => 'New',
                'last_name' => 'Guardian',
                'relationship' => 'uncle',
                'phone' => '1112223333',
                'status' => 'active',
            ]);

        $parentId = (int) $createResponse->json('data.id');

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.first_name', 'New');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->putJson('/api/parents/'.$parentId, [
                'status' => 'inactive',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'inactive');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenantA->id)
            ->deleteJson('/api/parents/'.$parentId)
            ->assertOk();

        $this->assertSoftDeleted('parents', ['id' => $parentId]);
    }

    private function createTenant(string $slug): Tenant
    {
        return Tenant::query()->create([
            'name' => ucfirst($slug).' Institute',
            'slug' => $slug,
            'domain' => $slug.'.localhost',
            'status' => 'active',
        ]);
    }

    private function createUserWithPermissions(Tenant $tenant, string $email, array $permissions): User
    {
        $user = User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Parent API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo($permissions);

        return $user;
    }
}
