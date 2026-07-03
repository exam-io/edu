<?php

namespace Modules\Settings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tenant_id' => $request->user()?->tenant_id,
            'language' => $this->language,
            'theme' => $this->theme,
            'timezone' => $this->timezone,
            'created_at' => $this->created_at?->format(DATE_ATOM),
            'updated_at' => $this->updated_at?->format(DATE_ATOM),
        ];
    }
}
