<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => $this->whenLoaded('permissions'),
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'api_version' => 'v1',
                'timestamp' => now()->toDateTimeString(),
                'path' => $request->fullUrl(),
                'http_method' => $request->method(),
            ],
        ];
    }
}
