<?php

namespace Modules\Branding\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'theme_mode' => $this['theme_mode'] ?? 'light',
            'tokens' => $this['tokens'] ?? [],
        ];
    }
}
