<?php

namespace Tests\Feature\Modules\Settings;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Tests\TestCase;

class SettingsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_read_current_settings_for_tenant(): void
    {
        $tenant = $this->createTenant('settings-a');
        $user = $this->createUser($tenant, 'settings-a@edus.test');

        UserSetting::query()->create([
            'user_id' => $user->id,
            'language' => 'en',
            'theme' => 'light',
            'timezone' => 'UTC',
        ]);

        Sanctum::actingAs($user);

        $response = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/settings');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.tenant_id', $tenant->id)
            ->assertJsonPath('data.theme', 'light');
    }

    public function test_user_can_update_their_settings(): void
    {
        $tenant = $this->createTenant('settings-b');
        $user = $this->createUser($tenant, 'settings-b@edus.test');

        $setting = UserSetting::query()->create([
            'user_id' => $user->id,
            'language' => 'en',
            'theme' => 'light',
            'timezone' => 'UTC',
        ]);

        Sanctum::actingAs($user);

        $response = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->putJson('/api/v1/settings/'.$setting->id, [
                'language' => 'hi',
                'theme' => 'dark',
                'timezone' => 'Asia/Kolkata',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.language', 'hi')
            ->assertJsonPath('data.theme', 'dark')
            ->assertJsonPath('data.timezone', 'Asia/Kolkata');
    }

    public function test_user_cannot_access_another_users_setting(): void
    {
        $tenant = $this->createTenant('settings-c');
        $userA = $this->createUser($tenant, 'settings-c-a@edus.test');
        $userB = $this->createUser($tenant, 'settings-c-b@edus.test');

        $settingB = UserSetting::query()->create([
            'user_id' => $userB->id,
            'language' => 'en',
            'theme' => 'light',
            'timezone' => 'UTC',
        ]);

        Sanctum::actingAs($userA);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/settings/'.$settingB->id)
            ->assertNotFound();
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

    private function createUser(Tenant $tenant, string $email): User
    {
        $user = User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Settings User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return $user;
    }
}
