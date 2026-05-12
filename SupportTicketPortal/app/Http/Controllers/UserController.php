<?php

namespace App\Http\Controllers;

use App\Http\Resources\V1\UserResource;
use App\Http\Services\UserService;
use App\Models\User;

class UserController extends BaseController
{
    public function __construct(UserService $service)
    {
        // Pass allowed includes for User
        parent::__construct($service, UserResource::class, ['roles', 'permissions']);
    }

    public function assignRole($id)
    {
        $this->service->assignRole($id);

        return response()->json([
            'message' => 'Role assigned successfully',
        ]);
    }

    public function agents()
    {
        $users = $this->service->getAgents();

        return UserResource::collection($users);
    }
}
