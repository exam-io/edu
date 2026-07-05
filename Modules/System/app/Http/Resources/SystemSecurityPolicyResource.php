<?php

namespace Modules\System\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemSecurityPolicyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'force_mfa' => (bool) $this->force_mfa,
            'session_ttl_minutes' => (int) $this->session_ttl_minutes,
            'password_rotation_days' => (int) $this->password_rotation_days,
            'allow_ip_restriction' => (bool) $this->allow_ip_restriction,
            'allowed_ip_ranges' => $this->allowed_ip_ranges ?? [],
            'strict_transport_security' => (bool) $this->strict_transport_security,
        ];
    }
}
