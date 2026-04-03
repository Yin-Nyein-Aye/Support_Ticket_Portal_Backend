<?php

namespace App\Http\Controllers;

use App\Http\Services\RoleService;
use App\Http\Resources\V1\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
    public function __construct(RoleService $service)
    {
        parent::__construct($service, RoleResource::class, ['permissions']);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'nullable|string'
        ]);

        return parent::store($request);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'guard_name' => 'nullable|string'
        ]);

        return parent::update($request, $id);
    }

    // ASSIGN PERMISSIONS TO ROLE (FIXED)
    public function assignPermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);

        // clear cache before syncing
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully'
        ]);
    }
}
