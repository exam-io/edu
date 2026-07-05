<?php

namespace Tests\Feature\Modules\Campaign;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CampaignApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_create_and_launch_flow(): void
    {
        $tenant = $this->createTenant('cmp-a');
        $user = $this->createUser($tenant, 'cmp-a@test.local');
        $recipient = $this->createUser($tenant, 'cmp-rec@test.local');

        Permission::findOrCreate('campaign.create', 'web');
        Permission::findOrCreate('campaign.view', 'web');
        Permission::findOrCreate('campaign.launch', 'web');
        $user->givePermissionTo(['campaign.create', 'campaign.view', 'campaign.launch']);

        Sanctum::actingAs($user);

        $store = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/campaigns', [
                'name' => 'Admissions Push',
                'subject' => 'Join us',
                'message' => 'Admissions open now.',
                'channels' => ['in_app'],
                'recipient_user_ids' => [$recipient->id],
            ])
            ->assertCreated();

        $id = (int) $store->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/campaigns/' . $id . '/launch')
            ->assertOk()
            ->assertJsonPath('data.status', 'launched');
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
            'name' => 'Campaign User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
