<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hrm_id' => $this->hrm_id,
            'user_no' => $this->user_no,
            'chinese_name' => $this->chinese_name,
            'english_name' => $this->english_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'is_valid' => $this->is_valid,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'registration_url' => config('services.nueip.base_uri') . 'register?sales=' . base64_encode($this->hrm_id),
        ];
    }
}
