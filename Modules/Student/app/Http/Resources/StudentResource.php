<?php

namespace Modules\Student\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'user_id' => $this->user_id,
            'admission_no' => $this->admission_no,
            'roll_no' => $this->roll_no,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'blood_group' => $this->blood_group,
            'phone' => $this->phone,
            'email' => $this->email,
            'photo' => $this->photo,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'emergency_contact' => $this->emergency_contact,
            'admission_date' => $this->admission_date?->toDateString(),
            'status' => $this->status,
            'parents' => $this->whenLoaded('parents'),
            'enrollments' => $this->whenLoaded('enrollments'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
