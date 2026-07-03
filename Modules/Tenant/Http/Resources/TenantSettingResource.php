<?php

namespace Modules\Tenant\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tenant_id' => $this->tenant_id,
            'theme' => $this->theme,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'logo' => $this->logo,
            'favicon' => $this->favicon,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
        ];
    }
}
