<?php

namespace Modules\Identity\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthContextResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this['user'];

        return [
            'user' => (new AuthUserResource($user))->resolve(),
            'tenant' => [
                'id' => $user->tenant?->id,
                'name' => $user->tenant?->name,
                'slug' => $user->tenant?->slug,
                'domain' => $user->tenant?->domain,
            ],
            'roles' => $user->getRoleNames()->values(),
            'permissions' => $user->getAllPermissions()->pluck('name')->values(),
            'can_manage_identity' => (bool) ($this['can_manage_identity'] ?? false),
        ];
    }
}
