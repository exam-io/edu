<?php

namespace Modules\Settings\Application\Services;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Settings\Application\Contracts\UserSettingsServiceInterface;
use Modules\Tenant\Application\Services\TenantContextService;

class UserSettingsService implements UserSettingsServiceInterface
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function currentForAuthenticatedUser(): UserSetting
    {
        $user = $this->authenticatedUser();
        $this->assertTenantContext($user);

        return UserSetting::query()->firstOrCreate(
            ['user_id' => $user->id],
            $this->defaultAttributes(),
        );
    }

    public function upsertForAuthenticatedUser(array $attributes): UserSetting
    {
        $user = $this->authenticatedUser();
        $this->assertTenantContext($user);

        $setting = UserSetting::query()->firstOrNew(['user_id' => $user->id]);
        $setting->fill($this->normalizeAttributes($attributes, $setting));
        $setting->save();

        return $setting->fresh();
    }

    public function showForAuthenticatedUser(int $settingId): UserSetting
    {
        $user = $this->authenticatedUser();
        $this->assertTenantContext($user);

        return $this->resolveOwnedSetting($user, $settingId);
    }

    public function updateForAuthenticatedUser(int $settingId, array $attributes): UserSetting
    {
        $user = $this->authenticatedUser();
        $this->assertTenantContext($user);

        $setting = $this->resolveOwnedSetting($user, $settingId);
        $setting->fill($this->normalizeAttributes($attributes, $setting));
        $setting->save();

        return $setting->fresh();
    }

    public function resetForAuthenticatedUser(int $settingId): UserSetting
    {
        $user = $this->authenticatedUser();
        $this->assertTenantContext($user);

        $setting = $this->resolveOwnedSetting($user, $settingId);
        $setting->fill($this->defaultAttributes());
        $setting->save();

        return $setting->fresh();
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return $user;
    }

    private function assertTenantContext(User $user): void
    {
        if (! $this->tenantContext->hasTenant()) {
            return;
        }

        if ((int) $this->tenantContext->requiredTenantId() !== (int) $user->tenant_id) {
            throw (new ModelNotFoundException())->setModel(UserSetting::class);
        }
    }

    private function resolveOwnedSetting(User $user, int $settingId): UserSetting
    {
        $setting = UserSetting::query()
            ->whereKey($settingId)
            ->where('user_id', $user->id)
            ->first();

        if ($setting === null) {
            throw (new ModelNotFoundException())->setModel(UserSetting::class, [$settingId]);
        }

        return $setting;
    }

    /** @return array<string, string> */
    private function defaultAttributes(): array
    {
        return [
            'language' => 'en',
            'theme' => 'light',
            'timezone' => 'UTC',
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, string>
     */
    private function normalizeAttributes(array $attributes, ?UserSetting $setting = null): array
    {
        return [
            'language' => isset($attributes['language']) ? (string) $attributes['language'] : (string) ($setting?->language ?? 'en'),
            'theme' => isset($attributes['theme']) ? (string) $attributes['theme'] : (string) ($setting?->theme ?? 'light'),
            'timezone' => isset($attributes['timezone']) ? (string) $attributes['timezone'] : (string) ($setting?->timezone ?? 'UTC'),
        ];
    }
}
