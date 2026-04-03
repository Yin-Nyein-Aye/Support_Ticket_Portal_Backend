<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,

            // Conditional loading (address only if requested)
            'address' => $this->whenLoaded('address'),
            'roles' => $this->whenLoaded('roles'),

            'permissions' => $this->whenLoaded('permissions'),
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'api_version' => 'v1',
                'timestamp'   => now()->toDateTimeString(), 
                'path'        => $request->fullUrl(),
                'http_method' => $request->method(),
            ]
        ];
    }

}
