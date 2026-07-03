<?php

namespace Tests\Feature\Modules\AI;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AIGenerationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_generation_index_and_store_require_permissions(): void
    {
        $tenant = $this->createTenant('ai-a');
        $userWithoutPermissions = $this->createUser($tenant, 'ai-user-no-perm@a.test');

        Sanctum::actingAs($userWithoutPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/ai-generation-requests')
            ->assertForbidden();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/ai-generation-requests', [
                'generation_type' => 'summary',
            ])
            ->assertForbidden();

        $userWithPermissions = $this->createUser($tenant, 'ai-user-with-perm@a.test');
        Permission::findOrCreate('ai.request.view', 'web');
        Permission::findOrCreate('ai.request.create', 'web');
        $userWithPermissions->givePermissionTo(['ai.request.view', 'ai.request.create']);

        Sanctum::actingAs($userWithPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/ai-generation-requests', [
                'generation_type' => 'summary',
                'prompt_text' => 'Create a concise summary.',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.generation_type', 'summary');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/ai-generation-requests?per_page=5')
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
            'name' => 'AI API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
