<?php

namespace Tests\Feature\Modules\Communication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CommunicationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_create_publish_and_history_list(): void
    {
        $tenant = $this->createTenant('com-a');
        $user = $this->createUser($tenant, 'com-a@test.local');
        $recipient = $this->createUser($tenant, 'com-rec@test.local');

        Permission::findOrCreate('communication.announcement.create', 'web');
        Permission::findOrCreate('communication.announcement.view', 'web');
        Permission::findOrCreate('communication.announcement.publish', 'web');
        Permission::findOrCreate('communication.history.view', 'web');
        $user->givePermissionTo([
            'communication.announcement.create',
            'communication.announcement.view',
            'communication.announcement.publish',
            'communication.history.view',
        ]);

        Sanctum::actingAs($user);

        $store = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/communications/announcements', [
                'title' => 'Holiday',
                'body' => 'Campus will be closed tomorrow.',
                'target_user_ids' => [$recipient->id],
                'channels' => ['in_app'],
            ])
            ->assertCreated();

        $id = (int) $store->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/communications/announcements/' . $id . '/publish')
            ->assertOk()
            ->assertJsonPath('data.status', 'published');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/communications/history')
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
            'name' => 'Communication User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
