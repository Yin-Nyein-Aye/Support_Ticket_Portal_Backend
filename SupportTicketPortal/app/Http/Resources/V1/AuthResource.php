<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this->id,
                'role_id' => (int) $this->role_id,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'full_name' => $this->full_name,
                'email' => $this->email,
                'role' => $this->role,
                'organisation_id' => $this->organisation_id,
                'organisation_name' => $this->organisation?->name,
            ],

            'access_token' => $this->access_token,
            'token_type' => $this->token_type ?? 'Bearer',
            'expires_in' => $this->expires_in ?? 3600,
            'refresh_token' => $this->refresh_token ?? null,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'api_version' => 'v1',
                'timestamp' => now()->toISOString(),
                'request_id' => $request->header('X-Request-Id') ?? uniqid(),
            ],
        ];
    }
}
