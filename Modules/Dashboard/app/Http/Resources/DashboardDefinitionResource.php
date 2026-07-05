<?php

namespace Modules\Dashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardDefinitionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'role_key' => $this->role_key,
            'is_default' => (bool) $this->is_default,
            'layout' => $this->layout,
            'widgets' => $this->whenLoaded('widgets', fn () => $this->widgets->map(function ($widget): array {
                return [
                    'id' => $widget->id,
                    'widget_key' => $widget->widget_key,
                    'title' => $widget->title,
                    'sort_order' => $widget->sort_order,
                    'config' => $widget->config,
                ];
            })->values()),
        ];
    }
}
