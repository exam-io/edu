<?php

namespace Modules\Parent\Application\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Parent\Application\Contracts\ParentProvisioningServiceInterface;
use Modules\Parent\Domain\Models\ParentProfile;

class ParentProvisioningService implements ParentProvisioningServiceInterface
{
    public function provisionParentUser(ParentProfile $parent): void
    {
        if ($parent->user_id !== null) {
            return;
        }

        $email = $parent->email ?? sprintf('parent.%d.%d@placeholder.eduos.local', $parent->tenant_id, $parent->id);

        $user = User::query()->create([
            'tenant_id' => $parent->tenant_id,
            'name' => trim($parent->first_name . ' ' . $parent->last_name),
            'email' => $email,
            'password' => Str::password(24),
            'status' => 'active',
        ]);

        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($parent->tenant_id);
        }

        $user->assignRole('Parent');

        $parent->update(['user_id' => $user->id]);
    }
}
