<?php

namespace Modules\Parent\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'relationship' => $this->relationship,
            'phone' => $this->phone,
            'email' => $this->email,
            'occupation' => $this->occupation,
            'address' => $this->address,
            'status' => $this->status,
            'students' => $this->whenLoaded('students'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
