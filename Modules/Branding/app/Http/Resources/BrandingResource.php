<?php

namespace Modules\Branding\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'logo_url' => $this->logo_url,
            'favicon_url' => $this->favicon_url,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'accent_color' => $this->accent_color,
            'font_family' => $this->font_family,
            'theme_mode' => $this->theme_mode,
            'extra_tokens' => $this->extra_tokens ?? [],
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
