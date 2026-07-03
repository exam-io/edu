<?php

namespace Modules\Settings\Application\Contracts;

use App\Models\UserSetting;

interface UserSettingsServiceInterface
{
    public function currentForAuthenticatedUser(): UserSetting;

    /** @param array<string, mixed> $attributes */
    public function upsertForAuthenticatedUser(array $attributes): UserSetting;

    public function showForAuthenticatedUser(int $settingId): UserSetting;

    /** @param array<string, mixed> $attributes */
    public function updateForAuthenticatedUser(int $settingId, array $attributes): UserSetting;

    public function resetForAuthenticatedUser(int $settingId): UserSetting;
}
