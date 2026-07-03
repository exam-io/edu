<?php

namespace Tests\Feature\Modules\ContentProcessing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ContentSourceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_source_endpoints_require_permissions(): void
    {
        $tenant = $this->createTenant('cp-a');
        $userWithoutPermissions = $this->createUser($tenant, 'cp-user-no-perm@a.test');

        Sanctum::actingAs($userWithoutPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/content-sources')
            ->assertForbidden();

        $userWithPermissions = $this->createUser($tenant, 'cp-user-with-perm@a.test');
        Permission::findOrCreate('content.processing.view', 'web');
        Permission::findOrCreate('content.processing.create', 'web');
        $userWithPermissions->givePermissionTo(['content.processing.view', 'content.processing.create']);

        Sanctum::actingAs($userWithPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/content-sources', [
                'title' => 'Chapter 1 Notes',
                'source_type' => 'text',
                'raw_text' => 'This is a sample content source for extraction.',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.source_type', 'text');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/content-sources?per_page=5')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 5);
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
            'name' => 'Content Processing API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
