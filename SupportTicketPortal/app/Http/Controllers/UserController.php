<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Services\UserService;
use App\Http\Resources\V1\UserResource;

class UserController extends BaseController
{
    public function __construct(UserService $service)
    {
        // Pass allowed includes for User
        parent::__construct($service, UserResource::class, ['address','roles', 'permissions']);
    }

    public function assignRole($id)
    {
        $user = User::findOrFail($id);

        $user->syncRoles("agent");
        $user->organisation_id = null;
        $user->save();

        return response()->json([
            'message' => 'Role assigned successfully'
        ]);
    }

    public function agents()
    {
        $users = User::role('agent')->with('roles')->get();
        return UserResource::collection($users);
    }
}
